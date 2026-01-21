<?php

namespace App\Http\Controllers\Produccion\Pozo;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Pozo\Activo;
use App\Models\Produccion\Pozo\MantenimientoCorrectivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MantenimientoController extends Controller
{
    // Muestra todos los mantenimientos (Histórico global)
    public function index()
    {
        $mantenimientos = MantenimientoCorrectivo::with('activo')->orderBy('fecha_falla_reportada', 'desc')->get();
        return view('produccion.pozos.mantenimientos.index', compact('mantenimientos'));
    }

    // Muestra el formulario para reportar una nueva falla (CREATE)
    public function create(Activo $activo)
    {
       // dd($activo);
        if ($activo->estatus_actual == 'EN_MANTENIMIENTO') {
            return redirect()->route('produccion.pozos.activos.index')->with('error', 'El Pozo' . $activo->nombre .' se en encuentra en '.$activo->estatus_actual.' Cambie de estado para poder crear otro mantenimiento');
        }

        return view('produccion.pozos.mantenimientos.create', compact('activo'));
    }

    // Almacena la falla y actualiza el estatus del activo (STORE)
    public function store(Request $request, Activo $activo)
    {
        $request->validate([
            'sintoma_falla' => 'required|string|max:500',
            'responsable' => 'nullable|string|max:255',
            'fecha_falla_reportada' => 'required',
        ]);

        // Aseguramos la atomicidad de la operación
        DB::transaction(function () use ($request, $activo) {

            // 1. Crear el registro de mantenimiento
            MantenimientoCorrectivo::create([
                'id_activo' => $activo->id,
                'fecha_falla_reportada' => $request->fecha_falla_reportada,
                'sintoma_falla' => $request->sintoma_falla,
                'responsable' => $request->responsable,
                // Los campos de cierre (trabajo_realizado, fecha_reinicio) se llenan después.
            ]);

            // 2. Actualizar el estatus del activo a 'EN_MANTENIMIENTO'
            $activo->estatus_actual = 'EN_MANTENIMIENTO';
            $activo->fecha_ultimo_cambio = now();
            $activo->save();
        });

        return redirect()->route('produccion.pozos.activos.show', $activo)
                         ->with('success', 'Falla reportada exitosamente. El activo "' . $activo->nombre . '" está ahora en Mantenimiento.');
    }

    // Muestra los detalles de un mantenimiento específico (SHOW)
    public function show(MantenimientoCorrectivo $mantenimiento)
    {
        return view('produccion.pozos.mantenimientos.show', compact('mantenimiento'));
    }

    // Muestra el formulario para cerrar o editar un mantenimiento (EDIT)
    public function edit(MantenimientoCorrectivo $mantenimiento)
    {
        // Se usa esta vista tanto para editar datos como para cerrar el evento.
        return view('produccion.pozos.mantenimientos.edit', compact('mantenimiento'));
    }

    // Actualiza los datos y realiza el cierre si es necesario (UPDATE)
    public function update(Request $request, MantenimientoCorrectivo $mantenimiento)
    {   

        $activo = $mantenimiento->activo;

        DB::transaction(function () use ($request, $mantenimiento, $activo) {
            
            $data = $request->only(['sintoma_falla', 'trabajo_realizado', 'costo_asociado', 'responsable']);
            $fecha_reinicio = $request->fecha_reinicio_operacion;
            
            // Lógica de CIERRE del mantenimiento
            if ($fecha_reinicio && !$mantenimiento->fecha_reinicio_operacion) {
                
                $data['fecha_reinicio_operacion'] = $fecha_reinicio;
                
                // Cálculo del Tiempo de Parada (en Horas)
                $fechaFalla = $mantenimiento->fecha_falla_reportada;
                $fechaReinicio = new \DateTime($fecha_reinicio);
                $diferencia = $fechaFalla->diff($fechaReinicio);
                
                // Calcular diferencia total en segundos y luego a horas
                $total_segundos = $diferencia->days * 86400 + $diferencia->h * 3600 + $diferencia->i * 60 + $diferencia->s;
                $data['tiempo_parada_horas'] = round($total_segundos / 3600, 2);

                // 3. Actualizar el estatus del activo a 'OPERATIVO'
                $activo->estatus_actual = 'OPERATIVO';
                $activo->fecha_ultimo_cambio = now();
                $activo->save();

                $mensaje = 'Mantenimiento cerrado exitosamente. Activo puesto en OPERATIVO.';
            } else {
                $mensaje = 'Mantenimiento actualizado exitosamente.';
            }

            $mantenimiento->update($data);

            return redirect()->route('produccion.pozos.mantenimientos.index')
                         ->with('success', $mensaje);
        });
        
    }

    // Elimina un registro de mantenimiento (DESTROY)
    public function destroy(MantenimientoCorrectivo $mantenimiento)
    {
        $activo = $mantenimiento->activo;
        $mantenimiento->delete();
        
        // **IMPORTANTE:** Aquí se podría agregar lógica para revisar si hay otros mantenimientos abiertos 
        // para el mismo activo, y si no los hay, volver el activo a PARADO o OPERATIVO.
        // Por simplicidad, asumimos que si se elimina, el usuario debe revisar el estatus del Activo manualmente.

        return redirect()->route('produccion.pozos.mantenimientos.index')
                         ->with('warning', 'Registro de mantenimiento eliminado.');
    }
}