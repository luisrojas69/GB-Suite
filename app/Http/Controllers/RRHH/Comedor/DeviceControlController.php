<?php

namespace App\Http\Controllers\RRHH\Comedor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\Gate;
use App\Models\RRHH\Comedor\DiningEmployee;
use Illuminate\Support\Facades\Artisan;

class DeviceControlController extends Controller
{
   
    public function index()
    {
      // Gate::authorize('controlar_dispositivo_comedor');
        
        // Intentamos obtener información básica para el estado inicial
        $zk = new ZKTeco(env('DEVICE_IP'), env('DEVICE_PORT') );
        $info = ['online' => false];
        if ($zk->connect()) {
            $info = [
                'online' => true,
                'name' => $zk->deviceName(),
                'version' => $zk->version(),
                'time' => $zk->getTime(),
                'platform' => $zk->platform(),
                'users' => count($zk->getUser())
            ];
            $zk->disconnect();
        }

        return view('RRHH.Comedor.device.control', compact('info'));
    }

    public function execute(Request $request)
    {
    //   Gate::authorize('controlar_dispositivo_comedor');
        
        $command = $request->command;
        $zk = new ZKTeco(env('DEVICE_IP'), env('DEVICE_PORT') );

        if (!$zk->connect()) {
            return response()->json(['error' => 'No se pudo establecer conexión con el dispositivo.'], 500);
        }

        try {
            switch ($command) {
                case 'restart': $zk->restart(); break;
                case 'enable': $zk->enableDevice(); break;
                case 'disable': $zk->disableDevice(); break;
                case 'sync_time': $zk->setTime(date('Y-m-d H:i:s')); break;
                case 'test_voice': $zk->testVoice(); break;
                case 'clear_admin': $zk->clearAdmin(); break;
                case 'shutdown': $zk->shutdown(); break;
                default: 
                    return response()->json(['error' => 'Comando no reconocido.'], 400);
            }
            
            return response()->json(['success' => 'Comando ejecutado: ' . strtoupper($command)]);
        } finally {
            $zk->disconnect();
        }
    }


    //Metodos de Gestion activa
    public function forceSync(Request $request)
    {
       // Gate::authorize('controlar_dispositivo_comedor');
        
        $type = $request->type; // 'attendance' o 'users'

        try {
            if ($type == 'attendance') {
                Artisan::call('zkteco:sync-comedor');
                $output = "Marcaciones sincronizadas.";
            } else {
                Artisan::call('zkteco:sync-users');
                $output = "Lista de empleados actualizada.";
            }

            return response()->json(['success' => $output]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al sincronizar: ' . $e->getMessage()], 500);
        }
    }


    public function pushToDevice2(Request $request)
    {
    //   Gate::authorize('controlar_dispositivo_comedor');
        
        $employee = DiningEmployee::findOrFail($request->employee_id);
        $zk = new ZKTeco(env('DEVICE_IP'), env('DEVICE_PORT') );

        if ($zk->connect()) {
            try {
                // El método setUser requiere: uid, userid, name, password, role, cardno
                // Usamos biometric_id tanto para uid como para userid para mantener consistencia
                $zk->setUser(
                    $employee->biometric_id, 
                    $employee->biometric_id, 
                    $employee->name, 
                    '', // Sin password por defecto
                    0,  // Rol de usuario normal
                    $employee->card_number ?? 0
                );
                return response()->json(['success' => "Empleado {$employee->name} enviado al biométrico."]);
            } finally {
                $zk->disconnect();
            }
        }
        return response()->json(['error' => 'No hay conexión con el equipo.'], 500);
    }

    public function pushToDevice(Request $request){
        $employee = DiningEmployee::findOrFail($request->employee_id);
        $zk = new ZKTeco(env('DEVICE_IP'), env('DEVICE_PORT') );

        if (!$zk->connect()) {
            Log::warning("COMEDOR-USERS: No se pudo conectar al equipo. Es posible que la VPN esté caída.");
            return;
        }

        try {
         
            $zk->setUser(
                    $employee->biometric_id, 
                    $employee->biometric_id, 
                    $employee->name, 
                    '', // Sin password por defecto
                    0,  // Rol de usuario normal
                    $employee->card_number ?? 0
                );
                return response()->json(['success' => "Empleado {$employee->name} enviado al biométrico."]);

        } catch (\Exception $e) {
            Log::error("COMEDOR-USERS: Error durante la sincronización: " . $e->getMessage());
        } finally {
            $zk->enableDevice();
            $zk->disconnect();
        }

    }
}