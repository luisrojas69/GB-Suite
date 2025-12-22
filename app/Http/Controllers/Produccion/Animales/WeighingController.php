<?php

namespace App\Http\Controllers\Produccion\Animales;

use App\Models\Produccion\Animales\Animal;
use App\Models\Produccion\Animales\Weighing;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WeighingController extends Controller
{
    // Muestra la vista de inicio del registro de pesajes (con o sin animal encontrado)
   public function create(Request $request)
    {
        // 1. Inicialización de variables (CLAVE para evitar el error compact())
        $animal = null;
        $lastWeighing = null;
        $error = null;
        
        // El 'iron_id' puede venir como parámetro GET en la URL
        $ironId = $request->input('iron_id'); 

        // 2. Lógica de Búsqueda
        if ($ironId) {
            $animal = Animal::where('iron_id', $ironId)
                            ->where('is_active', true)
                            ->first();

            if (!$animal) {
                $error = "Animal con ID/Tatuaje '{$ironId}' no encontrado, o está inactivo (dado de baja).";
            } else {
                // 3. Si el animal existe, buscar su último pesaje
                // Nota: La variable $lastWeighing queda definida aquí. Si no hay pesajes, será NULL, lo cual es correcto.
                $lastWeighing = Weighing::where('animal_id', $animal->id)
                                        ->orderByDesc('weighing_date')
                                        ->first();
            }
        }

        // 4. Retorna la vista, usando el path que usted especificó.
        // Como todas las variables ($animal, $ironId, $lastWeighing) están inicializadas, compact() funciona.
        return view('produccion.animales.weighings.create', compact('animal', 'ironId', 'lastWeighing', 'error'));
    }


    // Almacena un nuevo registro de pesaje
    public function store(Request $request)
    {
        // 1. Validación estricta de la entrada
        $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'weighing_date' => 'required|date|before_or_equal:today',
            'weight_kg' => 'required|numeric|min:1|max:99999.99', // Usamos decimal
            'notes' => 'nullable|string|max:255',
        ]);


        // 2. Comprobar duplicados por día (opcional, si se implementó la unique key en la migración)
        $exists = Weighing::where('animal_id', $request->animal_id)
                          ->where('weighing_date', $request->weighing_date)
                          ->exists();
                          
        if ($exists) {
            return back()->withInput()->with('error', 'Ya existe un registro de pesaje para este animal en la fecha seleccionada.');
        }

        // 3. Creación del registro
        Weighing::create($request->all());

        return redirect()->route('weighings.create')
                         ->with('success', 'Pesaje registrado exitosamente. Último peso: ' . $request->weight_kg . ' kg.');
    }
    
    // Función de ejemplo para mostrar historial (opcional)
    public function index()
    {
        $weighings = Weighing::with('animal')->orderByDesc('weighing_date')->paginate(30);
        return view('produccion.animales.weighings.index', compact('weighings'));
    }
}