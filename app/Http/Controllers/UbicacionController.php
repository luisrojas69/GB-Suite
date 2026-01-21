<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    public function index()
    {
        $ubicaciones = Ubicacion::all();
        return view('configuracion.ubicaciones.index', compact('ubicaciones'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:ubicaciones,nombre']);
        Ubicacion::create($request->all());
        return redirect()->back()->with('success', 'Ubicación creada correctamente.');
    }
}