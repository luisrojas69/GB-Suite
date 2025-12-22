<?php

namespace App\Http\Controllers\Produccion\Agro;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Agro\Molienda;
use App\Models\Produccion\Agro\Zafra;
use App\Models\Produccion\Agro\Destino;
use App\Models\Produccion\Agro\Contratista;
use App\Models\Produccion\Agro\Variedad;
use App\Models\Produccion\Areas\Tablon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MoliendaController extends Controller
{
    /**
     * Muestra una lista de los arrimes de molienda.
     */
    public function index()
    {
        // Precargar relaciones para optimizar la carga de la tabla
        $moliendas = Molienda::with(['zafra', 'tablon', 'destino', 'contratista'])
                             ->orderBy('fecha', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(20); // Paginación para grandes volúmenes
                             
        return view('produccion.agro.moliendas.index', compact('moliendas'));
    }

    /**
     * Muestra el formulario para crear un nuevo registro de molienda.
     */
    public function create()
    {
        // 1. Obtener la Zafra Activa (clave foránea principal)
        $zafra_activa = Zafra::where('estado', 'Activa')->first();

        // 2. Obtener los demás catálogos
        $tablones = Tablon::with('lote.sector')->where('estado', 'Activo')->get()->mapWithKeys(function ($t) {
            return [$t->id => $t->codigo_completo . ' - ' . $t->nombre . ' (' . $t->lote->sector->nombre . ')'];
        })->sort();

        $destinos = Destino::orderBy('nombre')->pluck('nombre', 'id');
        $contratistas = Contratista::orderBy('nombre')->pluck('nombre', 'id');
        $variedades = Variedad::orderBy('nombre')->pluck('nombre', 'id'); // Incluimos variedades si no se obtiene del tablón

        if (!$zafra_activa) {
            // Manejar el caso de que no haya zafra activa
            return redirect()->route('produccion.agro.moliendas.index')
                             ->with('error', 'No se puede registrar una molienda: Debe haber al menos una **Zafra Activa** en el sistema.');
        }

        return view('produccion.agro.moliendas.create', compact('zafra_activa', 'tablones', 'destinos', 'contratistas', 'variedades'));
    }

    /**
     * Almacena un nuevo registro de molienda.
     */
    public function store(Request $request)
    {
        $request->validate([
            'zafra_id' => 'required|exists:zafras,id',
            'tablon_id' => 'required|exists:tablones,id',
            'destino_id' => 'required|exists:destinos,id',
            'contratista_id' => 'required|exists:contratistas,id',
            'variedad_id' => 'required|exists:variedades,id',
            'boleto_remesa' => 'required|string|max:20|unique:moliendas,boleto_remesa',
            'fecha' => 'required|date',
            'peso_bruto' => 'required|numeric|min:0.01',
            'peso_tara' => 'required|numeric|min:0|lt:peso_bruto', 
            'brix' => 'nullable|numeric|min:0|max:100',
            'pol' => 'nullable|numeric|min:0|max:100',
            'rendimiento' => 'nullable|numeric|min:0|max:100',
        ], [
            'boleto_remesa.unique' => 'Ya existe un arrime registrado con este número de boleto.',
            'peso_tara.lt' => 'El Peso Tara debe ser menor que el Peso Bruto.',
        ]);

        // 1. Calcular Peso Neto
        $peso_neto = $request->peso_bruto - $request->peso_tara;
        $data = $request->all();
        $data['toneladas'] = $peso_neto; // Almacenamos el peso neto

        // 2. CALCULAR NUMERO_SOCA (Retoño)
        // Contar el número de moliendas históricas existentes para este Tablón.
        // Esto nos dice cuántas veces se ha cosechado este tablón.
        $moliendasAnteriores = Molienda::where('tablon_id', $request->tablon_id)->count();
        
        // El número de soca es (Cosechas Anteriores) + 1.
        // 0 Anteriores = Caña Planta (Soca #1)
        // 1 Anterior  = Soca 1 (Soca #2)
        // 2 Anteriores = Soca 2 (Soca #3)
        $numeroSoca = $moliendasAnteriores + 1;
        
        // Agregar el campo calculado al array de datos para la inserción
        $data['numero_soca'] = $numeroSoca;
        
        // 3. Crear el registro
        Molienda::create($data);

        // Uso de SweetAlert2 en el Store para el éxito inmediato (opcional, pero mejora UX)
        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'title' => '¡Arrime Registrado!',
                'message' => "El arrime con boleto **{$request->boleto_remesa}** ha sido guardado. Peso Neto: **" . number_format($peso_neto, 2) . " kg**. Soca **#{$numeroSoca}**.",
                'redirect' => route('produccion.agro.moliendas.index')
            ]);
        }
        
        return redirect()->route('produccion.agro.moliendas.index')
            ->with('success', 'Arrime registrado exitosamente.');
    }

    // ... show, edit, update, destroy (solo se genera create/store y el index para fines de este ejemplo)
    
    /**
     * Elimina el registro de molienda (SweetAlert2/AJAX).
     */
    public function destroy(Molienda $molienda)
    {
        try {
            $molienda->delete();
            return response()->json(['success' => true, 'message' => 'Arrime eliminado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al intentar eliminar el registro.']);
        }
    }

    // ... show, edit, update (placeholders)

    public function show(Molienda $molienda)
    {
        // Precargar relaciones
        $molienda->load(['zafra', 'tablon.lote.sector', 'destino', 'contratista', 'variedad']);
        return view('produccion.agro.moliendas.show', compact('molienda'));
    }

     public function edit(Molienda $molienda)
    {

        //dd($molienda);
        // Carga la lista de recursos necesarios para los Selects
        $tablones = Tablon::with('lote.sector')->get()->mapWithKeys(function ($t) {
            return [$t->id => $t->codigo_completo . ' - ' . $t->nombre . ' (' . $t->lote->sector->nombre . ')'];
        })->sort();
        
        // Asumiendo que estos modelos están correctamente importados (Destino, Contratista, Variedad, Zafra)
        $destinos = Destino::orderBy('nombre')->pluck('nombre', 'id');
        $contratistas = Contratista::orderBy('nombre')->pluck('nombre', 'id');
        $variedades = Variedad::orderBy('nombre')->pluck('nombre', 'id'); 
        $zafras = Zafra::orderBy('anio_inicio', 'desc')->pluck('nombre', 'id'); 

        // Se retorna la vista con la instancia de Molienda y los datos de los selects
        return view('produccion.agro.moliendas.edit', compact('molienda', 'zafras', 'tablones', 'destinos', 'contratistas', 'variedades'));
    }
    
    /**
     * Actualiza el arrime de molienda especificado.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Produccion\Agro\Molienda $molienda
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request, Molienda $molienda)
{
    // 1. Validaciones
    // Nota: tablon_id se mantiene como 'required|exists' para asegurar que el dato enviado 
    // por el campo hidden sea válido, aunque no se pueda cambiar en el front-end.


        $request->validate([
            'zafra_id' => [
                'required',
                    Rule::unique('moliendas', 'zafra_id')->ignore($molienda->id),
                ],
            'tablon_id' => 'required|exists:tablones,id',
            'destino_id' => 'required|exists:destinos,id',
            'contratista_id' => 'required|exists:contratistas,id',
            'variedad_id' => 'required|exists:variedades,id',
            'boleto_remesa' => [
            'required',
            'string',
            'max:20',
                Rule::unique('moliendas', 'boleto_remesa')->ignore($molienda->id),
            ],
            'fecha' => 'required|date',
            'peso_bruto' => 'required|numeric|min:0.01',
            'peso_tara' => 'required|numeric|min:0|lt:peso_bruto', 
            'brix' => 'nullable|numeric|min:0|max:100',
            'pol' => 'nullable|numeric|min:0|max:100',
            'rendimiento' => 'nullable|numeric|min:0|max:100',
        ], [
            'boleto_remesa.unique' => 'Ya existe un arrime registrado con este número de boleto.',
            'peso_tara.lt' => 'El Peso Tara debe ser menor que el Peso Bruto.',
    ]);


    // 2. Procesamiento de datos y cálculo de Toneladas
    $peso_neto = $request->peso_bruto - $request->peso_tara;
    
    dd($peso_neto);
    // Obtenemos los datos validados
    $data = $validated;
    
    // Añadimos el peso neto
    $data['toneladas'] = $peso_neto;
    
    // 3. Mantenemos el NUMERO_SOCA original (NO se cambia el tablón)
    $data['numero_soca'] = $molienda->numero_soca;

    // 4. Actualizar el registro
    $molienda->update($data);

    // Respuesta para AJAX / SweetAlert2 (opcional)
    if ($request->ajax()) {
        $numeroSocaFinal = $molienda->numero_soca;
        return response()->json([
            'success' => true, 
            'title' => '¡Arrime Actualizado!',
            'message' => "El arrime con boleto **{$request->boleto_remesa}** ha sido actualizado. Nuevo Peso Neto: **" . number_format($peso_neto, 2) . " kg**. Soca **#{$numeroSocaFinal}**.",
            'redirect' => route('produccion.agro.moliendas.index')
        ]);
    }

    return redirect()->route('produccion.agro.moliendas.index')
        ->with('success', 'Arrime actualizado exitosamente.');
}
}