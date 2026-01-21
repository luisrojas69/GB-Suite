<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AgroMonitoringService
{
    protected $apiKey;
    protected $baseUrl = 'http://api.agromonitoring.com/agro/1.0';

    public function __construct()
    {
        $this->apiKey = config('services.agromonitoring.key') ?? env('AGROMONITORING_API_KEY');
    }

    /**
     * Obtiene el clima actual basado en las coordenadas del centro del sector
     */
    public function getWeather($lat, $lon)
    {
        try {
            $response = Http::get("{$this->baseUrl}/weather", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->apiKey,
                'units' => 'metric' // Para grados Celsius
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error("Error AgroMonitoring Weather: " . $e->getMessage());
            return null;
        }
    }

    public function getForecast($lat, $lon)
    {
        try {
            $response = Http::get("{$this->baseUrl}/weather/forecast", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->apiKey,
                'units' => 'metric'
            ]);

            if ($response->successful()) {
                // AgroMonitoring devuelve una lista en la llave 'list' o directamente el array
                $data = $response->json();
                return $data['list'] ?? $data; 
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

}