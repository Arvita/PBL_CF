<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use App\Models\Sensor;
use Illuminate\Http\Request;

class FlutterController extends Controller
{
    public function getLastThreeSensors()
    {
        $this->middleware('auth:sanctum');
        $sensors = Sensor::with(['DetailSensor' => function ($query) {
            $query->latest();
        }])->get();

        $data = [];

        foreach ($sensors as $sensor) {
            $latestDetailSensor = $sensor->DetailSensor->first(); // Ambil detail sensor terakhir

            if ($latestDetailSensor) {
                $data[] = [
                    'message' => 'Data berhasil diterima.',
                    'sensor' => $sensor->sensor_name,
                    'temp' => $latestDetailSensor->temp,
                ];
            }
        }
        if (!empty($data)) {
            return response()->json(['message' => 'Data berhasil diterima.', 'sensors' => $data], 200);
        } else {
            return response()->json(['message' => 'Tidak ada data sensor.'], 200);
        }
    }

    public function getLastActuatorStatus()
    {
        // Implementasikan kode untuk mengambil status terakhir dari aktuator terakhir
        // Kemudian kembalikan response JSON
        // Mendapatkan status terakhir dari masing-masing aktuator
        $lastActuator = Actuator::latest()->first();
        if ($lastActuator) {
            return response()->json([
                'actuator_name' => $lastActuator->actuator_name,
                'status' => $lastActuator->status,
                'id' => $lastActuator->id
            ]);
        }
        return response()->json(['message' => 'No actuator found.'], 200);
    }

    public function updateActuatorStatus(Request $request, $id)
    {
        // Implementasikan kode untuk memperbarui status aktuator berdasarkan request
        // Kemudian kembalikan response JSON
        {
            $actuator = Actuator::findOrFail($id);
            $actuator->status = $request->input('status');
            $actuator->save();

            return response()->json(['message' => 'Status updated successfully']);
        }
    }
}
