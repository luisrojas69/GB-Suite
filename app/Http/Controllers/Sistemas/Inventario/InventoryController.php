<?php

namespace App\Http\Controllers\Sistemas\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sistemas\Inventario\Item;
use App\Models\Sistemas\Inventario\Assignment;
use App\Models\MedicinaOcupacional\Paciente;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use App\Exports\Sistemas\Inventario\InventarioExport;
use Maatwebsite\Excel\Facades\Excel;


use DB;
use Exception;


class InventoryController extends Controller
{


    /**
     * Listado principal de equipos
     */
    public function index(Request $request)
    {
        $group = $request->ajax() ? $request->input('group') : ($request->route()->defaults['group'] ?? 'ALL');
        
        if ($request->ajax()) {
            // 1. Iniciamos la consulta
            $model = Item::with(['currentAssignment.assignable', 'category', 'currentAssignment.location']);

            // 2. Aplicamos el filtro al objeto $model (No a $query)
            if ($group === 'IT') {
                $model->where('item_group', 'IT');
            } elseif ($group === 'ADMIN') {
                // Filtramos todo lo que no sea IT para la vista de administración
                $model->where('item_group', '!=', 'IT');
            }
            // Si es 'ALL', no entra en los IF y trae todo.

            return DataTables::of($model)
                ->addColumn('brand_model', function($row) {
                    return ($row->brand ?? 'S/M') . ' - ' . ($row->model ?? 'S/M');
                })
                ->addColumn('status_badge', function($row) {
                    // Mapeamos lo que viene de la DB a lo que queremos mostrar
                    $config = [
                        'disponible'     => ['class' => 'success', 'label' => 'Disponible'],
                        'asignado'       => ['class' => 'info',    'label' => 'Asignado'],
                        'mantenimiento'  => ['class' => 'warning', 'label' => 'Dañado'], // <--- Aquí el cambio
                        'desincorporado' => ['class' => 'danger',  'label' => 'Desincorporado'],
                    ];

                    $state = $config[$row->status] ?? ['class' => 'secondary', 'label' => $row->status];

                    return '<span class="badge badge-'.$state['class'].'">'.$state['label'].'</span>';
                })
                ->addColumn('responsable', function($row) {
                    $assignment = $row->currentAssignment;
                    if (!$assignment) return '<span class="text-muted">Sin asignar</span>';
                    
                    // Usamos el morphMap o el namespace completo según tu configuración
                    $icon = str_contains($assignment->assignable_type, 'Paciente') ? '👤 ' : '🏢 ';
                    
                    // Intentamos obtener el nombre del responsable
                    $nombre = $assignment->assignable->nombre ?? $assignment->assignable->nombre_completo ?? 'N/D';
                    return $icon . $nombre;
                })

                ->addColumn('ubicacion', function($row) {
                    return $row->currentAssignment->location->nombre ?? 'N/A';
                })
                ->addColumn('actions', function($row) {
                    $btn = '<div class="btn-group" role="group">';
                    
                    // 1. Botón Editar (Datos básicos)
                    $btn .= '<button class="btn btn-sm btn-outline-secondary btn-editar" data-id="'.$row->id.'" title="Editar Datos"><i class="fas fa-edit"></i></button>';

                    // 2. Botón Asignar / Retornar
                    if ($row->status == 'asignado') {
                        // Si está asignado, mostramos botón de Retorno (Desasignar)
                        $btn .= '<button class="btn btn-sm btn-warning btn-retornar" data-id="'.$row->id.'" title="Retornar a Almacén"><i class="fas fa-undo"></i></button>';
                        
                        // 3. Botón Descargar Acta (Solo si está asignado)
                        if ($row->currentAssignment) {
                            $btn .= '<a href="'.route('inventario.downloadActa', $row->currentAssignment->id).'" target="_blank" class="btn btn-sm btn-dark" title="Imprimir Acta"><i class="fas fa-file-pdf"></i></a>';
                        }
                    } else {
                        $btn .= '<button class="btn btn-sm btn-primary btn-asignar" data-id="'.$row->id.'" data-nombre="'.$row->name.'" title="Asignar"><i class="fas fa-user-plus"></i></button>';
                    }

                    // 4. Botón Cambio de Estatus (Dañado/Desincorporado)
                    $btn .= '<button class="btn btn-sm btn-danger btn-status" data-id="'.$row->id.'" title="Cambiar Estado"><i class="fas fa-triangle-exclamation"></i></button>';

                    // 5. Botón Ver Detalle (Vista Show)
                    $btn .= '<a href="'.route('inventario.show', $row->id).'" class="btn btn-sm btn-info" title="Ver Historial"><i class="fas fa-eye"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status_badge', 'responsable', 'actions'])
                ->make(true);
        }

        $ubicaciones = \App\Models\GB\Ubicacion::orderBy('nombre', 'asc')->get();
        
        // Filtrado de categorías para el modal según el módulo
        $categorias = \App\Models\Sistemas\Inventario\Category::when($group !== 'ALL', function($q) use ($group) {
            return $q->where('modulo', $group);
        })->orderBy('nombre', 'asc')->get();

        return view('sistemas.inventario.index', compact('ubicaciones', 'categorias', 'group'));
    }



    public function show($id)
    {
        // Cargamos el ítem con su categoría y TODO su historial de asignaciones
        $item = Item::with(['category', 'assignments.assignable', 'assignments.location'])
                    ->findOrFail($id);

        return view('sistemas.inventario.show', compact('item'));
    }


    // Para cargar los datos en el modal de edición
    public function edit($id) {
        return response()->json(Item::findOrFail($id));
    }

    // Para procesar la actualización
    public function update(Request $request, $id) {
        $item = Item::findOrFail($id);
        $item->update($request->only(['name', 'brand', 'model', 'serial']));
        return response()->json(['success' => true]);
    }



    /**
     * Registrar un nuevo equipo al inventario
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:inv_categories,id',
            'brand'       => 'nullable|string|max:100',
            'model'       => 'nullable|string|max:100',
            'serial'      => 'nullable|unique:inv_items,serial', // Evita duplicados
            'asset_tag'   => 'nullable|unique:inv_items,asset_tag', // Evita duplicados
            'status'      => 'required|in:disponible,dañado,mantenimiento',
            'item_group' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB máximo
        ], [
            'serial.unique'    => 'Este número de serial ya está registrado en el sistema.',
            'asset_tag.unique' => 'Este número de activo ya ha sido asignado a otro equipo.',
        ]);

        try {
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                // Guarda en storage/app/public/inventario/fotos
                $path = $request->file('image')->store('inventario/fotos', 'public');
                $data['image_path'] = $path;
            }

            Item::create($data);

            return response()->json(['success' => 'Equipo registrado correctamente.']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }



    /**
     * Lógica central de ASIGNACIÓN y TRANSFERENCIA
     */
    public function assign(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inv_items,id',
            'target_id' => 'required', // ID del empleado o depto
            //'target_type' => 'required|in:employee,department', 
            'location_id' => 'required',
            'accessories' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $item = Item::findOrFail($request->item_id);

            // 1. Cerrar asignación previa si existe
            $currentAssignment = Assignment::where('item_id', $item->id)
                ->whereNull('returned_at')
                ->first();

            if ($currentAssignment) {
                $currentAssignment->update([
                    'returned_at' => now(),
                ]);
            }

            // 2. Determinar el Model para la relación polimórfica
            $modelType = ($request->target_type == 'App\Models\MedicinaOcupacional\Paciente') 
                ? 'App\Models\MedicinaOcupacional\Paciente' 
                : 'App\Models\GB\Departamento';

            // 3. Crear la nueva asignación (Historial)
            $newAssignment = Assignment::create([
                'item_id'       => $item->id,
                'assignable_id'   => $request->target_id,
                'assignable_type' => $modelType,
                'location_id'   => $request->location_id,
                'accessories'   => $request->accessories,
                'assigned_at'   => now(),
            ]);

            // 4. Actualizar estado del equipo
            $item->update(['status' => 'asignado']);

            DB::commit();

            return response()->json([
                'success' => 'Transferencia procesada correctamente.',
                'assignment_id' => $newAssignment->id // Útil para disparar el PDF de inmediato
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error en transferencia: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Cambiar status a Dañado o Desincorporado
     */
    public function changeStatus(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        
        // Mapeo de seguridad para SQL Server
        $statusMap = [
            'dañado'         => 'mantenimiento', // Usamos un término estándar
            'desincorporado' => 'desincorporado',
            'disponible'     => 'disponible',
            'asignado'       => 'asignado'
        ];

        $nuevoStatus = $statusMap[$request->status] ?? $request->status;

        // Si es mantenimiento o desincorporado, cerramos asignación
        if (in_array($nuevoStatus, ['mantenimiento', 'desincorporado'])) {
            \App\Models\Sistemas\Inventario\Assignment::where('item_id', $item->id)
                ->whereNull('returned_at')
                ->update(['returned_at' => now()]);
        }

        $item->update(['status' => $nuevoStatus]);
        
        return response()->json(['success' => 'Estado del equipo actualizado correctamente.']);
    }


    public function downloadActa($id)
    {
        $assignment = Assignment::with(['item', 'assignable', 'location'])->findOrFail($id);
        
        // Nombre del archivo: Acta_IT_00045_GranjaBoraure_2026-01-10.pdf
        $filename = "Acta_" . $assignment->item->item_group . "_" . 
                    str_pad($assignment->id, 5, '0', STR_PAD_LEFT) . 
                    "_GB_" . date('Y-m-d') . ".pdf";

        $pdf = \PDF::loadView('sistemas.inventario.pdf.acta_entrega', compact('assignment'));
        
        return $pdf->stream($filename);
    }

    public function buscarResponsable(Request $request)
    {
        $term = $request->get('q');

        // Buscamos en Empleados
        $empleados = DB::table('med_pacientes') // Ajusta al nombre real de tu tabla
            ->select('id', 'nombre_completo as text', DB::raw("'App\\Models\\MedicinaOcupacional\\Paciente' as type"))
            ->where('nombre_completo', 'LIKE', "%$term%")
            ->where('status', 'A') // Solo empleados activos de Profit
            ->limit(10);

        // Buscamos en Departamentos
        $resultados = DB::table('departamentos') // Ajusta al nombre real de tu tabla
            ->select('id', 'nombre_completo as text', DB::raw("'App\\Models\\GB\\Departamento' as type"))
            ->where('nombre_completo', 'LIKE', "%$term%")
            ->union($empleados)
            ->get();

        // Formateamos para que Select2 lo entienda y diferencie
        $data = $resultados->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => ($item->type == 'App\\Models\\MedicinaOcupacional\\Paciente' ? '👤 ' : '🏢 ') . $item->text,
                'type' => $item->type
            ];
        });

        return response()->json($data);
    }


    public function export(Request $request) 
    {
        $group = $request->get('group', 'ALL');
        $fecha = now()->format('d-m-Y_Hi');
        
        return Excel::download(new InventarioExport($group), "inventario_{$group}_{$fecha}.xlsx");
    }


    //Metodo para retornar equipos con nota

    public function returnItem(Request $request)
    {

        $item = Item::findOrFail($request->item_id);
        $assignment = Assignment::where('item_id', $item->id)->whereNull('returned_at')->first();

        if ($assignment) {
            $assignment->update([
                'returned_at' => now(),
                'return_notes' => $request->notes
            ]);
            
            $item->update(['status' => 'disponible']);

            return response()->json([
                'success' => 'Equipo recibido en almacén.',
                'assignment_id' => $assignment->id // Enviamos el ID para el PDF
            ]);
        }
        
        return response()->json(['error' => 'No hay asignación activa'], 422);
    }


    public function downloadActaRetorno($id)
    {
        $assignment = Assignment::with(['item', 'assignable', 'location'])->findOrFail($id);
        
        $pdf = \PDF::loadView('sistemas.inventario.pdf.acta_retorno', compact('assignment'));
        
        return $pdf->stream("Acta_Retorno_IT_{$assignment->id}.pdf");
    }
    

    public function downloadActaLote($userId)
    {
        // Buscamos todas las asignaciones ACTIVAS del usuario que no han sido devueltas
        // Ajusta según tu base de datos (por ejemplo, si usas 'status' en la tabla assignments)
        $assignments = Assignment::where('assignable_id', $userId)
                        ->whereHas('item', function($q) {
                            $q->where('status', 'asignado'); // Solo los que tiene actualmente
                        })
                        ->with(['item', 'location', 'assignable'])
                        ->get();

        if ($assignments->isEmpty()) {
            return back()->with('error', 'No hay activos asignados para este usuario.');
        }

        $pdf = \PDF::loadView('sistemas.inventario.pdf.acta_lote', compact('assignments'));
        
        // Opcional: Nombre de archivo dinámico
        $nombreArchivo = "Acta_Lote_" . str_replace(' ', '_', $assignments->first()->assignable->nombre_completo) . ".pdf";

        return $pdf->stream($nombreArchivo);
    }


    //Asignaciones masivas
    public function massAssignment(Request $request)
    {
        $request->validate([
            'items'         => 'required|array',
            'assignable_id' => 'required', // El ID elegido en el Select2
            'target_type'   => 'required', // 'employee' o 'department' (enviado desde el JS)
            'location_id'   => 'required',
        ]);

        try {
            $result = \DB::transaction(function () use ($request) {
                $createdIds = [];

                // Determinamos el Model para la relación polimórfica (Igual que en tu assign simple)
                $modelType = ($request->target_type == 'employee' || $request->target_type == 'App\Models\MedicinaOcupacional\Paciente') 
                    ? 'App\Models\MedicinaOcupacional\Paciente' 
                    : 'App\Models\GB\Departamento';

                foreach ($request->items as $itemId) {
                    $item = Item::findOrFail($itemId);
                    
                    // 1. Opcional: Cerrar asignación previa si el ítem ya estaba asignado
                    Assignment::where('item_id', $item->id)
                        ->whereNull('returned_at')
                        ->update(['returned_at' => now()]);

                    // 2. Crear la nueva asignación
                    $newAssignment = Assignment::create([
                        'item_id'         => $item->id,
                        'assignable_id'   => $request->assignable_id,
                        'assignable_type' => $modelType,
                        'location_id'     => $request->location_id,
                        'assigned_at'     => now(),
                    ]);

                    // 3. Actualizar estado del ítem
                    $item->update(['status' => 'asignado']);
                    
                    $createdIds[] = $newAssignment->id;
                }
                
                return $createdIds;
            });

            return response()->json([
                'success' => true,
                'message' => 'Lote de equipos asignado correctamente.',
                'ids'     => $result, // Lista de IDs de asignaciones creadas
                'assignable_id' => $request->assignable_id 
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
