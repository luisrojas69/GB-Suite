<?php

namespace App\Http\Controllers\Logistica\Taller;

use App\Http\Controllers\Controller;
use App\Models\Logistica\Taller\Activo;
use App\Models\Produccion\Labores\LaborMaquinariaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Para transacciones o consultas complejas

class ActivoController extends Controller
{
    
    public function index()
    {
        Gate::authorize('gestionar_activos');
        
        // Cargamos conteos de relaciones para mostrar indicadores en la tabla
        $activos = Activo::withCount(['laboresDetalle', 'servicios', 'programacionesMP' => function($q) {
            $q->where('status', '!=', 'Completado');
        }])
        ->orderBy('codigo')
        ->get(); // Si prefieres Datatables JS, quitamos paginate. Si no, mantenlo.

        // Estadísticas para las Mini-Cards superiores
        $stats = [
            'total' => $activos->count(),
            'operativos' => $activos->where('estado_operativo', 'Operativo')->count(),
            'taller' => $activos->whereIn('estado_operativo', ['En Mantenimiento', 'Fuera de Servicio'])->count(),
        ];
        
        return view('taller.activos.index', compact('activos', 'stats'));
    }

    public function create()
    {
        // Requerimiento: Poder crear nuevos activos
        Gate::authorize('gestionar_activos');
        
        // Aquí se podrían pasar los ENUMs para el select (tipo, unidad, etc.)
        return view('taller.activos.create');
    }

    // ... (Métodos store, show, edit, update, destroy protegidos de forma similar)
    public function destroy(Activo $activo)
    {
        Gate::authorize('gestionar_activos');
        // Usar soft deletes o cambiar estado a 'Desincorporado'
        $activo->estado_operativo = 'Desincorporado';
        $activo->save();
        
        return redirect()->route('activos.index')->with('success', 'Activo desincorporado correctamente.');
    }

    /**
     * Almacena un activo recién creado en el almacenamiento.
     */
    public function store(Request $request)
    {

       // 1. Autorización: Se mantiene el permiso general para gestionar (CRUD)
        Gate::authorize('gestionar_activos');

        // 2. Validación de datos: Adaptada a los campos de la tabla 'activos'
        $data = $request->validate([
            // Identificación
            'codigo' => ['required', 'string', 'max:50', 'unique:activos,codigo'],
            'nombre' => ['required', 'string', 'max:255'],
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validación Imagen
            'placa' => ['nullable', 'string', 'max:50'],
            'tipo' => ['required', Rule::in(['Tractor', 'Camión', 'Camioneta', 'Moto', 'Cosechadora', 'Zorra', 'Otro'])], // Corresponde al ENUM 'tipo'
            'marca' => ['nullable', 'string', 'max:100'],
            'modelo' => ['nullable', 'string', 'max:100'],
            
            // Uso y Status
            'departamento_asignado' => ['required', 'string', 'max:100'],
            'estado_operativo' => ['required', Rule::in(['Operativo', 'En Mantenimiento', 'Fuera de Servicio', 'Desincorporado'])], // Corresponde al ENUM 'estado_operativo'
            'lectura_actual' => ['required', 'integer', 'min:0'],
            'unidad_medida' => ['required', Rule::in(['KM', 'HRS'])], // Corresponde al ENUM 'unidad_medida'

            // Fechas y metadata
            'fecha_adquisicion' => ['nullable', 'date'],
        ]);

        // 3. Si tiene imagen la guardamos
        if ($request->hasFile('imagen')) {
            // Guarda la imagen en storage/app/public/activos
            $ruta = $request->file('imagen')->store('activos', 'public');
            $data['imagen'] = $ruta;
        }

        // 3. Creación del Activo
        // Nota: El método create() funciona si todos los campos están en $fillable del modelo.
       Activo::create($data);

        return redirect()->route('activos.index')
            ->with('success', 'El Activo se ha creado exitosamente.');
    }
    /**
     * Muestra los detalles de un activo específico.
     */
    public function show(Activo $activo)
    {
        // 1. Autorización: Usamos el permiso de visualización, que puede ser 'gestionar_activos' o 'ver_activos'.
        // Usaremos 'ver_activos' si es granular, o 'gestionar_activos' si es el único permiso CRUD.
        // Usaremos 'gestionar_activos' para ser consistente con tu ejemplo.
        Gate::authorize('gestionar_activos'); 

        // 2. Carga Eager Loading de las relaciones necesarias.
        // Esto previene múltiples consultas a la base de datos (N+1).
        $activo->load([
            // Cargamos todas las lecturas registradas.
            'lecturas' => function ($query) {
                // Además, cargamos el usuario que registró la lectura (registrador_id -> User model)
                $query->with('registrador')->latest('fecha_lectura')->take(5); // Traemos las 5 lecturas más recientes
            },
            // Cargamos la Programación de Mantenimiento Preventivo (MP).
            // Si tu modelo ProgramacionMP tiene la relación 'checklist', la cargamos también.
            'programacionesMP' => function ($query) {
                 // Asumiendo que ProgramacionMP tiene una relación 'checklist'
                 $query->with('checklist')->where('status', '!=', 'Completado')->latest();
            },
            // La relación hasOne 'ultimaLectura' ya está optimizada, pero la incluimos por claridad.
            // Si ya cargamos 'lecturas', esta puede ser redundante, pero es útil para tenerla como objeto directo.
            'ultimaLectura', 
        ]);

        $labores = $activo->laboresDetalle()
        ->with(['registro.labor', 'operador']) // Eager loading para no saturar la DB
        ->latest()
        ->take(10)
        ->get();

        // 3. Pasar el activo cargado a la vista.
        return view('taller.activos.show', compact('activo', 'labores'));
    }

    /**
     * Muestra el formulario para editar un activo específico.
     */
   public function edit(Activo $activo)
    {
        // 1. Autorización: Se mantiene el permiso general para gestionar (CRUD)
        Gate::authorize('gestionar_activos');

        // Se retorna la vista de edición con la instancia del activo
        return view('taller.activos.edit', compact('activo'));
    }
/**
     * Actualiza el activo específico en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Logistica\Taller\Activo  $activo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Activo $activo)
    {
        // 1. Autorización
        Gate::authorize('gestionar_activos');

        // 2. Validación de datos
        $request->validate([
            // Identificación
            'codigo' => [
                'required', 
                'string', 
                'max:50', 
                // 🛑 CLAVE: Ignorar el ID del activo que estamos editando
                Rule::unique('activos', 'codigo')->ignore($activo->id),
            ],
            'nombre' => ['required', 'string', 'max:255'],
            'placa' => ['nullable', 'string', 'max:50'],
            'tipo' => ['required', Rule::in(['Tractor', 'Camión', 'Camioneta', 'Moto', 'Cosechadora', 'Zorra', 'Otro'])],
            'marca' => ['nullable', 'string', 'max:100'],
            'modelo' => ['nullable', 'string', 'max:100'],
            
            // Uso y Status
            'departamento_asignado' => ['required', 'string', 'max:100'],
            'estado_operativo' => ['required', Rule::in(['Operativo', 'En Mantenimiento', 'Fuera de Servicio', 'Desincorporado'])],
            
            // 🛑 CLAVE: La nueva lectura debe ser MAYOR o IGUAL que la lectura actual registrada.
            'lectura_actual' => ['required', 'integer', 'min:' . $activo->lectura_actual], 
            'unidad_medida' => ['required', Rule::in(['KM', 'HRS'])],

            // Fechas y metadata
            'fecha_adquisicion' => ['nullable', 'date'],
        ]);

        // 3. Actualización del Activo
        $activo->update($request->all());

        return redirect()->route('activos.show', $activo->id) // Redirigir a la vista de detalle (show) es la mejor práctica después de una edición
            ->with('success', "El Activo **{$activo->codigo}** se ha actualizado exitosamente.");
    }

    /**
     * Elimina (desincorpora) un activo específico.
     */
    public function destroy2(Activo $activo)
    {
        Gate::authorize('eliminar_activos');
        
        // Lógica de "Soft Delete" por cambio de estado
        try {
            DB::transaction(function () use ($activo) {
                
                // 1. Marcar el activo como Desincorporado
                $activo->estado_operativo = 'Desincorporado';
                $activo->save();

                // 2. Si es necesario, registrar el evento de desincorporación
                // $activo->eventos()->create(['tipo' => 'Desincorporación', 'fecha' => now(), 'motivo' => '...']);
            });

        } catch (\Exception $e) {
             return redirect()->route('activos.index')->with('error', 'Error al desincorporar el activo: ' . $e->getMessage());
        }

        return redirect()->route('activos.index')
                         ->with('success', 'Activo desincorporado correctamente.');
    }
}