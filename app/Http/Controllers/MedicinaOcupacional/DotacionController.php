<?php

namespace App\Http\Controllers\MedicinaOcupacional;

use App\Http\Controllers\Controller;
use App\Models\MedicinaOcupacional\Paciente;
use App\Models\MedicinaOcupacional\Dotacion;
use Illuminate\Http\Request;
use Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DB;


class DotacionController extends Controller
{

    public function index()
    {
        // Si la petición es AJAX (para el DataTable)
        if (request()->ajax()) {
            $dotaciones = Dotacion::with('paciente')->orderBy('id', 'desc')->get();
            return response()->json(['data' => $dotaciones]);
        }
        return view('MedicinaOcupacional.dotaciones.index');
    }

     public function index2()
    {
        // Si la petición es AJAX (para el DataTable)
       $dotaciones = Dotacion::with('paciente')->orderBy('id', 'desc')->get();
        return view('MedicinaOcupacional.dotaciones.index_con_stock', compact('dotaciones'));
    }

    public function show($id)
    {
        $dotacion = Dotacion::with(['paciente', 'user'])->findOrFail($id);
        return response()->json($dotacion);
    }

    public function destroy($id)
    {
        Dotacion::destroy($id);
        return response()->json(['success' => true]);
    }

    public function create($paciente_id)
    {
        $paciente = Paciente::findOrFail($paciente_id);
        $stockProfit = DB::connection('sqlsrv_administrativo')
            ->table('art')
            ->select('co_art', 'art_des', 'stock_act')
            ->where('co_art', 'LIKE', '308%')
            ->where('stock_act', '>', 0)
            ->get();

        return view('MedicinaOcupacional.dotaciones.create', compact('paciente' , 'stockProfit'));
    }

    public function store(Request $request)
    {

        $request->validate([
        'paciente_id' => 'required',
        'firma_digital' => 'required', // <--- Esto evitará que sea null
        'motivo' => 'required'],
        [
            'firma_digital.required' => 'La firma del trabajador es obligatoria para este proceso legal.'
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['fecha_entrega'] = now();
        
        // Limpiar valores de checkboxes
        $data['calzado_entregado'] = $request->has('calzado_entregado');
        $data['pantalon_entregado'] = $request->has('pantalon_entregado');
        $data['camisa_entregado'] = $request->has('camisa_entregado');

        Dotacion::create($data);

        return redirect()->route('medicina.dotaciones.index')
                         ->with('success', 'Dotación de EPP registrada correctamente.');
    }

    //En lugar de restar, modificaremos el formulario para que, al cargar, consulte a Profit cuánto hay disponible de cada artículo 308
    public function create_con_stock($paciente_id)
    {
        $paciente = Paciente::findOrFail($paciente_id);
        
        // Consultamos stock directamente de Profit (Solo lectura)
        // Filtramos por la línea 308 (EPP) y traemos el stock actual
        $stockProfit = DB::connection('sqlsrv_administrativo')
            ->table('art')
            ->select('co_art', 'art_des', 'stock_act')
            ->where('co_art', 'LIKE', '308%')
            ->where('stock_act', '>', 0)
            ->get();

        return view('MedicinaOcupacional.dotaciones.create_con_stock', compact('paciente', 'stockProfit'));
    }

    //Antes de guardar, verificamos cuándo fue la última entrega. Esto evita el uso excesivo de recursos.
    public function store_con_stock(Request $request)
    {
        // Verificamos la última entrega de este paciente
        $ultimaEntrega = Dotacion::where('paciente_id', $request->paciente_id)
            ->where('calzado_entregado', true)
            ->latest()
            ->first();

        if ($ultimaEntrega) {
            $mesesTranscurridos = $ultimaEntrega->created_at->diffInMonths(now());
            
            // Si han pasado menos de 6 meses (parámetro de Granja Boraure)
            if ($mesesTranscurridos < 6 && !$request->has('autorizacion_especial')) {
                return back()->with('warning_dotacion', "El trabajador recibió calzado hace solo $mesesTranscurridos meses. ¿Desea autorizar esta reposición excepcional?");
            }
        }

        // Si pasa la validación, guardamos la SOLICITUD (Status: Pendiente por Despacho)
        $dotacion = new Dotacion($request->all());
        $dotacion->estatus = 'PENDIENTE'; // El almacenista lo cambiará a 'DESPACHADO'
        $dotacion->qr_token = Str::random(40);
        $dotacion->save();

        return redirect()->route('medicina.dotaciones.index_con_stock')->with('success', 'Solicitud de Dotación generada. El trabajador puede retirar en Almacén.');
    }


    //Esta consulta le dirá al Ingeniero quiénes están "vencidos" o por vencerse según la fecha de su última entrega.
    // Método en el controlador para la vista de alertas
    public function alertasRedotacion()
    {
        // Buscamos trabajadores cuya última dotación de botas fue hace más de 170 días (~6 meses)
        $alertas = DB::table('med_dotaciones as d1')
            ->join('med_pacientes as p', 'd1.paciente_id', '=', 'p.id')
            ->select('p.nombre_completo', 'p.des_depart', 'd1.fecha_entrega')
            ->whereRaw('d1.id = (SELECT MAX(id) FROM med_dotaciones d2 WHERE d2.paciente_id = d1.paciente_id AND d2.calzado_entregado = 1)')
            ->where('d1.fecha_entrega', '<', now()->subMonths(6))
            ->get();

        return view('MedicinaOcupacional.dotaciones.alertas', compact('alertas'));
    }


    //Para que el analista de Profit no trabaje doble, creamos una vista que genere el resumen de "Salidas del Día" que ellos deben cargar en su sistema.
    public function reporteParaProfit2(Request $request)
    {
        $fecha = $request->get('fecha', today()->toDateString());
        
        $entregas = Dotacion::whereDate('fecha_despacho_almacen', $fecha)
            ->where('entregado_en_almacen', 1)
            ->get();

        // Esto se puede exportar a Excel para que Profit lo importe o se use como guía de carga
        return view('MedicinaOcupacional.dotaciones.reporte_profit', compact('entregas', 'fecha'));
    }


    public function reporteConsumo(Request $request)
    {
        // Rango de fechas (por defecto el mes actual)
        $desde = $request->get('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->get('hasta', now()->endOfMonth()->toDateString());

        $consumo = DB::table('med_dotaciones as d')
            ->join('med_pacientes as p', 'd.paciente_id', '=', 'p.id')
            ->select(
                'p.des_depart',
                DB::raw('SUM(CASE WHEN d.calzado_entregado = 1 THEN 1 ELSE 0 END) as total_botas'),
                DB::raw('SUM(CASE WHEN d.pantalon_entregado = 1 THEN 1 ELSE 0 END) as total_pantalones'),
                DB::raw('SUM(CASE WHEN d.camisa_entregado = 1 THEN 1 ELSE 0 END) as total_camisas')
            )
            ->whereBetween('d.fecha_entrega', [$desde, $hasta])
            ->groupBy('p.des_depart')
            ->get();

        return view('MedicinaOcupacional.dotaciones.reporte_consumo', compact('consumo', 'desde', 'hasta'));
    }

    public function imprimirTicket($id)
    {
        $dotacion = Dotacion::with('paciente')->findOrFail($id);
        // Generamos el QR que apunta a una ruta de validación
        $qrCode = QrCode::size(150)->generate(route('medicina.dotaciones.validar', $dotacion->qr_token));
        
        return view('MedicinaOcupacional.dotaciones.ticket_pdf', compact('dotacion', 'qrCode'));
    }

    public function validarEpp($token)
    {
        $dotacion = Dotacion::where('qr_token', $token)->first();

        if (!$dotacion) {
            return view('medicina.dotaciones.validacion_resultado', ['error' => 'Ticket Inválido o Falsificado.']);
        }

        return view('medicinaOcupacional.dotaciones.validacion_resultado', compact('dotacion'));
    }

    public function confirmarDespacho($id)
    {
        $dotacion = Dotacion::findOrFail($id);
        $dotacion->entregado_en_almacen = true;
        $dotacion->fecha_despacho_almacen = now();
        $dotacion->save();

        return back()->with('success', 'Entrega registrada en sistema correctamente - Recuerde aplicar el Ajuste de Salida en profit Plus.');
    }


    public function reporteParaProfit(Request $request)
    {
        $fecha = $request->get('fecha', date('Y-m-d'));

        // Subconsulta para normalizar los datos
        $subquery = DB::table('med_dotaciones')
            ->select(DB::raw("CONCAT('308-BOTA-', calzado_talla) as co_art"), DB::raw('1 as cantidad'))
            ->where('calzado_entregado', true)
            ->where('entregado_en_almacen', true)
            ->whereDate('fecha_despacho_almacen', $fecha)
            ->unionAll(
                DB::table('med_dotaciones')
                    ->select(DB::raw("CONCAT('308-JEAN-', pantalon_talla) as co_art"), DB::raw('1 as cantidad'))
                    ->where('pantalon_entregado', true)
                    ->where('entregado_en_almacen', true)
                    ->whereDate('fecha_despacho_almacen', $fecha)
            )
            ->unionAll(
                DB::table('med_dotaciones')
                    ->select(DB::raw("CONCAT('308-CAMISA-', camisa_talla) as co_art"), DB::raw('1 as cantidad'))
                    ->where('camisa_entregado', true)
                    ->where('entregado_en_almacen', true)
                    ->whereDate('fecha_despacho_almacen', $fecha)
            );

        // Agrupamos el resultado de la unión
        $resumen = DB::table(DB::raw("({$subquery->toSql()}) as t"))
            ->mergeBindings($subquery) // Importante para que las fechas funcionen en la subconsulta
            ->select('co_art', DB::raw('SUM(cantidad) as total'))
            ->groupBy('co_art')
            ->get();

        return view('MedicinaOcupacional.dotaciones.reporte_profit', compact('resumen', 'fecha'));
    }


}