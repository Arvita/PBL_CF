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
        $lastThreeSensors = Sensor::latest()->take(2)->with(['DetailSensor' => function ($query) {
            $query->latest();
        }])->get();

        $lastActuator = Actuator::latest()->take(2)->get();
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
        





        // $data = [];

        // foreach ($sensors as $sensor) {
        //     $sensor_temps = $sensor->DetailSensor->pluck('temp')->toArray();
        //     $sensor_updated_at = $sensor->DetailSensor->pluck('updated_at')->toArray(); // Menambahkan updated_at
        //     $data[] = [
        //         'label' => $sensor->sensor_name,
        //         'data' => array_combine($sensor_updated_at, $sensor_temps) // Menggabungkan updated_at dan temp
        //     ];
        // }

        // dd($data);

        return response()->json(['data' => $data]);
    }

    public function updateStatus($id, Request $request)
    {
        $status = $request->input('status');

        $actuator = Actuator::find($id);
        if ($actuator) {
            $actuator->status = $status;
            $actuator->save();
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui'], 200);
        }
        return response()->json(['success' => false, 'message' => 'Aktuator tidak ditemukan'], 404);
    }
}
