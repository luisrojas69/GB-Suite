<?php

namespace App\Http\Controllers\Produccion\Pozo;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Pozo\Activo;
use App\Models\Produccion\Pozo\MantenimientoCorrectivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivoController extends Controller
{
    // Muestra la lista de todos los activos (Pozos y Estaciones)
    public function index()
    {
        $activos = Activo::orderBy('estatus_actual', 'desc')->get();
        // 
        return view('produccion.pozos.activos.index', compact('activos'));
    }

    // Muestra el formulario para crear un nuevo activo
    public function create()
    {
        $pozos = Activo::where('tipo_activo', 'POZO')->get(); // Para asignar a estaciones
        return view('produccion.pozos.activos.create', compact('pozos'));
    }

    // Almacena un nuevo activo en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_activo' => 'required|in:POZO,ESTACION_REBOMBEO',
            'estatus_actual' => 'required|in:OPERATIVO,PARADO,EN_MANTENIMIENTO',
            'ubicacion' => 'required|string',
            // ... otras validaciones según el tipo
        ]);

        Activo::create([
            'nombre' => $request->nombre,
            'ubicacion' => $request->ubicacion,
            'tipo_activo' => $request->tipo_activo,
            'subtipo_pozo' => $request->subtipo_pozo,
            'id_pozo_asociado' => $request->id_pozo_asociado,
            'estatus_actual' => $request->estatus_actual,
            'fecha_ultimo_cambio' => now(), // Se establece la fecha inicial
            // ...
        ]);

        return redirect()->route('produccion.pozos.activos.index')
                         ->with('success', 'Activo creado exitosamente.');
    }

    // Muestra los detalles de un activo específico
    public function show(Activo $activo)
    {
        // Carga los mantenimientos y aforos recientes para la vista de detalle
        $mantenimientos = $activo->mantenimientos()->latest()->take(5)->get();
        $aforos = $activo->aforos() ? $activo->aforos()->latest()->take(5)->get() : collect();
        
        return view('produccion.pozos.activos.show', compact('activo', 'mantenimientos', 'aforos'));
    }

    // Muestra el formulario para editar un activo existente
    public function edit(Activo $activo)
    {
        $pozos = Activo::where('tipo_activo', 'POZO')->get();
        return view('produccion.pozos.activos.edit', compact('activo', 'pozos'));
    }

    // Actualiza el activo en la base de datos
    public function update(Request $request, Activo $activo)
    {
        // Validaciones...

        $activo->update([
            'nombre' => $request->nombre,
            'ubicacion' => $request->ubicacion,
            // ...
        ]);

        return redirect()->route('produccion.pozos.activos.index')
                         ->with('info', 'Activo actualizado exitosamente.');
    }

    // Elimina un activo
    public function destroy(Activo $activo)
    {
        // Se recomienda usar transacciones para asegurar que las FK se manejen correctamente.
        DB::transaction(function () use ($activo) {
            $activo->mantenimientos()->delete();
            if ($activo->tipo_activo === 'POZO') {
                $activo->aforos()->delete();
            }
            $activo->delete();
        });

        return redirect()->route('produccion.pozos.activos.index')
                         ->with('warning', 'Activo eliminado permanentemente.');
    }

    // Lógica AJAX para cambiar ESTATUS de forma rápida (USADO EN EL INDEX)
    public function cambiarEstatus(Request $request, Activo $activo)
    {
        $request->validate([
            'estatus' => 'required|in:OPERATIVO,PARADO,EN_MANTENIMIENTO',
        ]);

        $activo->estatus_actual = $request->estatus;
        $activo->fecha_ultimo_cambio = now();
        $activo->save();

        // Respuesta AJAX
        return response()->json(['success' => true, 'message' => 'Estatus de ' . $activo->nombre . ' actualizado a ' . $request->estatus]);
    }

    public function dashboard()
    {
        // 1. Datos para los Tiles (Totales)
        $totalActivos = Activo::count();
        $pozos = Activo::where('tipo_activo', 'POZO')->get();
        $estaciones = Activo::where('tipo_activo', 'ESTACION_REBOMBEO')->get();

        // 2. Datos para el Gráfico de Estatus
        $estatusData = Activo::select('estatus_actual', DB::raw('count(*) as count'))
                             ->groupBy('estatus_actual')
                             ->pluck('count', 'estatus_actual');

        // 3. Datos para Mantenimientos (KPIs)
        $mantenimientosAbiertos = MantenimientoCorrectivo::whereNull('fecha_reinicio_operacion')->count();
        
        // Cálculo de MTTR (Tiempo Medio para Reparar) en Horas
        $mantenimientosCerrados = MantenimientoCorrectivo::whereNotNull('fecha_reinicio_operacion')->get();
        $mttr = $mantenimientosCerrados->avg('tiempo_parada_horas');

        // 4. Pozos con el Caudal más bajo (Alerta Hidrológica)
        // Encuentra el último aforo para cada pozo y ordena
        $aforosRecientes = DB::table('aforos as a')
            ->select('a.*', 'p.nombre as pozo_nombre', DB::raw('ROW_NUMBER() OVER(PARTITION BY a.id_pozo ORDER BY a.fecha_medicion DESC) as rn'))
            ->join('pozos_y_estaciones as p', 'a.id_pozo', '=', 'p.id')
            ->where('p.tipo_activo', 'POZO')
            ->get()
            ->where('rn', 1) // Filtra solo el más reciente
            ->sortBy('caudal_medido_lts_seg')
            ->take(5);

        return view('produccion.pozos.dashboard', compact(
            'totalActivos',
            'pozos',
            'estaciones',
            'estatusData',
            'mantenimientosAbiertos',
            'mttr',
            'aforosRecientes'
        ));
    }
    
}