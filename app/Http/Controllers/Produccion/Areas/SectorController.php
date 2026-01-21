<?php

namespace App\Http\Controllers\Produccion\Areas;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Areas\Sector;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Illuminate\Support\Facades\DB;


class SectorController extends Controller
{
    /**
     * Muestra una lista de todos los sectores.
     */
    public function index()
    {
        $sectores = Sector::withCount(['lotes', 'tablones'])
        ->with(['ultimaLluvia']) // Relación que ya tienes en el modelo
        ->get();
        return view('produccion.areas.sectores.index', compact('sectores'));

    }

    /**
     * Muestra el formulario para crear un nuevo sector.
     */
    public function create()
    {
        return view('produccion.areas.sectores.create');
    }

    /**
     * Almacena un nuevo sector en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo_sector' => [
                                    'required', 
                                    'string', 
                                    'max:10', // Aumentamos un poco por seguridad
                                    Rule::unique('sectores', 'codigo_sector')->ignore($sector->id ?? null),
                                    'regex:/^[a-zA-Z0-9\s]+$/' // Añadimos \s para permitir espacios si lo deseas
                                ],
            'nombre' => 'required|string|max:100',
            'geometria' => 'required', 
        ]);

        $sector = new Sector($request->except('geometria'));
        $wkt = Polygon::fromJson($request->geometria)->toWkt();

        // Usamos STGeomFromText y luego .MakeValid() para asegurar compatibilidad
        $sector->geometria = DB::raw("geometry::STGeomFromText('{$wkt}', 0).MakeValid()");
        $sector->save();

        return redirect()->route('produccion.areas.sectores.index')->with('success', '✅ Sector creado.');
    }


    public function update(Request $request, $id)
    {
        $sector = Sector::findOrFail($id);
        
        $request->validate([
            'codigo_sector' => ['required', 'string', 'max:10', Rule::unique('sectores', 'codigo_sector')->ignore($id)],
            'nombre' => 'required|string|max:100',
        ]);

        $sector->fill($request->except('geometria'));

        if ($request->has('geometria')) {
            if ($request->geometria) {
                $polygon = Polygon::fromJson($request->geometria);
                $wkt = $polygon->toWkt();
                // Usamos 4326 que es el estándar para mapas web (GPS)
                $sector->geometria = DB::raw("geometry::STGeomFromText('{$wkt}', 4326).MakeValid()");
            } else {
                $sector->geometria = null;
            }
        }

        $sector->save();
        return redirect()->route('produccion.areas.sectores.show', $sector->id)->with('success', '✅ Sector actualizado.');
    }


    /**
     * Muestra la información detallada de un sector específico.
     */
    public function show($id)
    {
        $sector = Sector::with(['lotes.tablones' => function($query) {
            $query->addSelect('*')
                  ->addSelect(DB::raw('geometria.STAsText() as geometria_wkt'));
        }])
        ->select('*')
        ->addSelect(DB::raw('geometria.STAsText() as geometria_wkt'))
        ->findOrFail($id);

        // Cálculos de área y conteos
        $conteoTablones = 0;
        $hectareasTotales = 0;
        foreach($sector->lotes as $lote) {
            $conteoTablones += $lote->tablones->count();
            $hectareasTotales += $lote->tablones->sum('hectareas_documento');
        }

        // KPIs Pluviometría
        $acumuladoMes = $sector->pluviometrias()
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('cantidad_mm');

        $ultimaLluvia = $sector->pluviometrias()
            ->where('cantidad_mm', '>', 0)
            ->latest('fecha')
            ->first();
            
        $diasSinLluvia = 'N/A';

        if ($ultimaLluvia) {
            // diffInDays por defecto devuelve un entero
            // Usamos startOfDay() en ambas fechas para que no afecten las horas
            $diasSinLluvia = (int) now()->startOfDay()->diffInDays($ultimaLluvia->fecha->startOfDay());
        }

        // Para el área, si quieres que se vea más limpio en las tarjetas:
        $hectareasTotales = round($hectareasTotales, 2);

        // Preparar geometrías de los tablones para el mapa
        foreach ($sector->lotes as $lote) {
            foreach ($lote->tablones as $tablon) {
                $tablon->geometria_objeto = $tablon->geometria_wkt 
                    ? Polygon::fromWkt($tablon->geometria_wkt) 
                    : null;
            }
        }

        // Procesar geometría para el mapa
        $sector->geometria_objeto = $sector->geometria_wkt ? Polygon::fromWkt($sector->geometria_wkt) : null;

        // Obtener el centro del polígono para la API de clima
        
        $lat = 9.960669;
        $lon = -70.234770;

        if ($sector->geometria_objeto) {
            // Obtenemos el array de anillos (el primero es el exterior)
            // Intentamos el método directo de la interfaz Geometry
            $coords = $sector->geometria_objeto->toArray(); 
            
            // El formato de toArray() para Polygon suele ser: ['type' => 'Polygon', 'coordinates' => [ [ [lon, lat], ... ] ] ]
            if (isset($coords['coordinates'][0][0])) {
                $lon = $coords['coordinates'][0][0][0];
                $lat = $coords['coordinates'][0][0][1];
            }
        }
        // Llamamos al servicio
        $agroService = new \App\Services\AgroMonitoringService();
        $clima = $agroService->getWeather($lat, $lon);
        $pronostico = $agroService->getForecast($lat, $lon);

        return view('produccion.areas.sectores.show', compact(
            'sector', 'conteoTablones', 'hectareasTotales', 
            'acumuladoMes', 'diasSinLluvia', 'clima', 'pronostico'
        ));

    }
    /**
     * Muestra el formulario para editar un sector existente.
     */
    public function edit($id) // Cambiamos a $id para usar el select raw
    {
        $sector = Sector::select('*')
            ->addSelect(DB::raw('geometria.STAsText() as geometria_wkt'))
            ->findOrFail($id);

        // Procesar la geometría para el mapa
        $sector->geometria_objeto = null;
        if ($sector->geometria_wkt) {
            $sector->geometria_objeto = Polygon::fromWkt($sector->geometria_wkt);
        }

        return view('produccion.areas.sectores.edit', compact('sector'));
    }

    /**
     * Actualiza un sector existente en la base de datos.
     */

    /**
     * Elimina un sector de la base de datos.
     * La eliminación en cascada (definida en la migración) se encargará de borrar Lotes y Tablones asociados.
     */
    public function destroy(Sector $sector)
    {
        $nombreSector = $sector->nombre;
        
        // Política de seguridad: Spatie debería haberlo manejado, pero es una buena práctica la verificación final.
        if (auth()->user()->cannot('eliminar_sectores')) {
             return redirect()->route('produccion.areas.sectores.index')
                              ->with('error', '❌ Permiso denegado para eliminar sectores.');
        }

        try {
            $sector->delete();
            return redirect()->route('produccion.areas.sectores.index')
                             ->with('success', '🗑️ Sector "' . $nombreSector . '" y todas sus áreas asociadas han sido eliminados.');
        } catch (\Exception $e) {
            // Manejo de errores si la eliminación falla por alguna razón (ej. restricción de BD no definida correctamente)
            return redirect()->route('produccion.areas.sectores.index')
                             ->with('error', '❌ Error al eliminar el sector: ' . $e->getMessage());
        }
    }
}