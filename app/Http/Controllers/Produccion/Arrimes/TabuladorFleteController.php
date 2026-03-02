<?php

namespace App\Http\Controllers\Produccion\Arrimes;

use App\Http\Controllers\Controller;
use App\Models\Produccion\Areas\Sector;
use Illuminate\Http\Request;

class TabuladorFleteController extends Controller
{
    public function index()
    {
        $sectores = Sector::withCount(['lotes', 'tablones'])->orderBy('codigo_sector', 'asc')->get();
        return view('produccion.arrimes.fletes.tabulador', compact('sectores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tarifa_flete' => 'required|numeric|min:0'
        ]);

        $sector = Sector::findOrFail($id);
        $sector->update([
            'tarifa_flete' => $request->tarifa_flete
        ]);

        return response()->json(['success' => true, 'message' => 'Tarifa actualizada correctamente.']);
    }
}