<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class FlutterController extends Controller
{
    public function getLastThreeSensors()
    {
        $this->middleware('auth:sanctum');
        $latestSensor = Sensor::latest()->first();

        if ($latestSensor) {
            $detailSensor = $latestSensor->DetailSensor()->latest()->first(); // Get the most recent DetailSensor related to the most recent Sensor

            if ($detailSensor) {
                $data = [
                    'message' => 'Data berhasil diterima.',
                    'sensor' => $latestSensor->sensor_name,
                    'temp' => $detailSensor->temp,
                ];
            } else {
                $data = [
                    'message' => 'No DetailSensor found for the most recent Sensor.',
                ];
            }
        } else {
            $data = [
                'message' => 'No Sensor found.',
            ];
        }

        return response()->json($data, 200);
    }
}
