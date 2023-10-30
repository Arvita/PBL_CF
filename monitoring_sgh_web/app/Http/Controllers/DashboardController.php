<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DetailSensor;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $lastThreeSensors = Sensor::latest()->with(['DetailSensor' => function ($query) {
            $query->latest();
        }])->get();
        // $sensors = Sensor::with(['DetailSensor' => function($query) {
        //     $query->latest();
        // }])->get();

        // $sensorData = [];

        // foreach ($sensors as $sensor) {
        //     $latestDetailSensor = $sensor->DetailSensor->first(); // Ambil detail sensor terakhir

        //     if ($latestDetailSensor) {
        //         $sensorData[] = [
        //             'sensor' => $sensor->sensor_name,
        //             'temp' => $latestDetailSensor->temp,
        //         ];
        //     }
        // }

        // dd($sensorData);

        $lastActuator = Actuator::latest()->get();
        return view('dashboard', compact('lastThreeSensors', 'lastActuator'));
    }
    public function getChartData()
    {
        $sensors = Sensor::with(['DetailSensor' => function ($query) {
            $query->select('id_sensors', 'temp', 'updated_at') // Mengambil kolom updated_at
                ->orderBy('updated_at', 'desc')
                ->limit(10);
        }])->get();

        $data = [];

        foreach ($sensors as $sensor) {
            $sensor_updated_at = [];  // Array untuk menyimpan label
            $sensor_temps = [];       // Array untuk menyimpan data temperatur

            foreach ($sensor->DetailSensor as $entry) {
                // $timestamp = Carbon::parse($entry->updated_at)->format('H:i'); // Ubah format ke 'HH:mm'
                $timestamp = $entry->updated_at; // Ubah format ke 'HH:mm'
                $sensor_updated_at[] = $timestamp;
                $sensor_temps[] = $entry->temp; // Akses kolom temperatur
            }

            $data[] = [
                'label' => $sensor->sensor_name,
                'data' => array_combine($sensor_updated_at, $sensor_temps)
            ];
        }

        return response()->json(['data' => $data]);
    }

    public function updateStatus($id, Request $request)
    {
        $status = $request->input('status');

        $actuator = Actuator::find($id);
        if ($actuator) {
            if ($status == 1)
                $actuator->status = $status;
            else
                $actuator->status = 0;
            $actuator->save();
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui'], 200);
        }
        return response()->json(['success' => false, 'message' => 'Aktuator tidak ditemukan'], 404);
    }
}
