<?php

namespace App\Exports\Produccion\Pluviometria;

use App\Models\Produccion\Pluviometria\RegistroPluviometrico;
use App\Models\Produccion\Areas\Sector;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class PluviometriaMatrizExport implements FromView
{
    protected $desde, $hasta;

    public function __construct($desde, $hasta) {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function view(): View
    {
        $fechaInicio = Carbon::parse($this->desde);
        $fechaFin = Carbon::parse($this->hasta);
        
        $sectores = Sector::orderBy('nombre', 'asc')->get();
        
        // Obtenemos los registros en el rango
        $registros = RegistroPluviometrico::whereBetween('fecha', [$this->desde, $this->hasta])
                        ->get()
                        ->groupBy('id_sector')
                        ->map(fn($item) => $item->keyBy(fn($r) => Carbon::parse($r->fecha)->format('Y-m-d')));

        // Generamos array de fechas para las columnas
        $fechas = [];
        for ($date = $fechaInicio->copy(); $date->lte($fechaFin); $date->addDay()) {
            $fechas[] = $date->format('Y-m-d');
        }

        return view('produccion.pluviometria.exports.pluviometria_matriz_excel', [
            'sectores' => $sectores,
            'registros' => $registros,
            'fechas' => $fechas
        ]);
    }
}