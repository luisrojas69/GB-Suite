<?php

namespace App\Http\Controllers\Produccion\Pozo;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Pozo\Activo;
use App\Models\Produccion\Pozo\Aforo;
use Illuminate\Http\Request;

class AforoController extends Controller
{
    // Muestra todos los aforos (Histórico global de mediciones)
    public function index()
    {
        $aforos = Aforo::with('pozo')->orderBy('fecha_medicion', 'desc')->get();
        // 
        return view('produccion.pozos.aforos.index', compact('aforos'));
    }

    // Muestra el formulario para crear un nuevo aforo
    public function create(Request $request)
    {

        // Se puede recibir el ID del pozo desde la vista de Activos/Show
        $pozoId = $request->input('pozo_id');
        $pozos = Activo::where('tipo_activo', 'POZO')->where('estatus_actual', 'OPERATIVO')->orderBy('nombre')->get();
        
        return view('produccion.pozos.aforos.create', compact('pozos', 'pozoId'));
    }

    // Almacena un nuevo aforo
    public function store(Request $request)
    {
        $request->validate([
            'id_pozo' => 'required|exists:activos,id',
            'fecha_medicion' => 'required|date',
            'caudal_medido_lts_seg' => 'required|numeric|min:0',
            'nivel_estatico' => 'nullable|numeric|min:0',
            'nivel_dinamico' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
        ]);
        
        $aforo = Aforo::create($request->all());

        return redirect()->route('produccion.pozos.activos.show', $aforo->pozo)
                         ->with('success', 'Aforo registrado exitosamente para el pozo ' . $aforo->pozo->nombre . '.');
    }

    // Muestra los detalles de un aforo (incluyendo contexto histórico)
    public function show(Aforo $aforo)
    {
        $pozo = $aforo->pozo;
        // Obtener datos históricos del pozo para generar el gráfico de tendencia
        $historico = Aforo::where('id_pozo', $pozo->id)
                           ->orderBy('fecha_medicion', 'asc')
                           ->get();

        // Se puede añadir lógica de alerta: si el caudal actual es el más bajo, mostrar advertencia.
        return view('produccion.pozos.aforos.show', compact('aforo', 'pozo', 'historico'));
    }

    // Muestra el formulario para editar un aforo
    public function edit(Aforo $aforo)
    {
        $pozos = Activo::where('tipo_activo', 'POZO')->orderBy('nombre')->get();
        return view('produccion.pozos.aforos.edit', compact('aforo', 'pozos'));
    }

    // Actualiza el aforo
    public function update(Request $request, Aforo $aforo)
    {
        $request->validate([
            'id_pozo' => 'required|exists:activos,id',
            'fecha_medicion' => 'required|date',
            'caudal_medido_lts_seg' => 'required|numeric|min:0',
            'nivel_estatico' => 'nullable|numeric|min:0',
            'nivel_dinamico' => 'nullable|numeric|min:0',
            // ...
        ]);

        $aforo->update($request->all());

        return redirect()->route('produccion.pozos.aforos.show', $aforo)
                         ->with('info', 'Aforo actualizado exitosamente.');
    }

    // Elimina un aforo
    public function destroy(Aforo $aforo)
    {
        $pozo = $aforo->pozo;
        $aforo->delete();
        
        return redirect()->route('produccion.pozos.activos.show', $pozo)
                         ->with('warning', 'Registro de aforo eliminado.');
    }
}