<?php

namespace App\Http\Controllers\RRHH\Comedor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RRHH\Comedor\DiningRecord;
use App\Models\RRHH\Comedor\MealType;
use App\Exports\RRHH\Comedor\ConsumoExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Support\Facades\Gate;

class ReporteComedorController extends Controller
{
    public function index()
    {
       // Gate::authorize('ver_reportes_comedor');
        $mealTypes = MealType::all();
        return view('RRHH.Comedor.reports.index', compact('mealTypes'));
    }

    public function generar(Request $request)
    {
       // Gate::authorize('ver_reportes_comedor');

        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'formato' => 'required|in:pdf,excel'
        ]);

        $records = DiningRecord::with(['mealType'])
            ->whereBetween('punch_time', [$request->fecha_inicio . ' 00:00:00', $request->fecha_fin . ' 23:59:59'])
            ->orderBy('punch_time', 'asc')
            ->get();

        if ($request->formato == 'excel') {
            return Excel::download(new ConsumoExport($request->fecha_inicio, $request->fecha_fin), 'Consumo_Comedor.xlsx');
        }

        // Para PDF usando Snappy
        $data = [
            'records' => $records,
            'inicio' => $request->fecha_inicio,
            'fin' => $request->fecha_fin,
            'total' => $records->sum('cost')
        ];

        return PDF::loadView('RRHH.Comedor.reports.pdf_consumo', $data)
            ->setPaper('letter')
            ->setOption('margin-bottom', '10mm')
            ->inline('Consumo_Comedor.pdf');
    }
}