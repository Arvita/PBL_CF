<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    // Show all sensor
    public function index()
    {
        $sensor = Sensor::all();
        return view('sensor.index',compact('sensor'));
    }

    // Show the form for creating a new sensor
    public function create()
    {
        return view('sensor.create');
    }

    // Store a newly created sensor in the database
    public function store(Request $request)
    {
        $request->validate([
            'sensor_name' => 'required|string|max:255',
            ]);

        Sensor::create([
            'name' => $request->name,
            ]);

        return redirect()->route('sensor.index')->with('success', 'Sensor created successfully');
    }

    // Show the form for editing the specified sensor
    public function edit($id)
    {
        $sensor = Sensor::findOrFail($id);
        return view('sensor.edit', compact('sensor'));
    }

    // Update the specified sensor in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'sensor_name' => 'required|string|max:255',
            ]);

        $sensor = Sensor::findOrFail($id);
        $sensor->update([
            'sensor_name' => $request->name,
            ]);

        return redirect()->route('sensor.index')->with('success', 'Sensor updated successfully');
    }

    // Remove the specified sensor from the database
    public function destroy($id)
    {
        $sensor = Sensor::findOrFail($id);
        $sensor->delete();

        return redirect()->route('sensor.index')->with('success', 'Sensor deleted successfully');
    }
}
