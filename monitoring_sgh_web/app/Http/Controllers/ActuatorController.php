<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use Illuminate\Http\Request;

class ActuatorController extends Controller
{
    // Show all actuator
    public function index()
    {
        $actuator = Actuator::all();
        return view('actuator.index',compact('actuator'));
    }

    // Show the form for creating a new actuator
    public function create()
    {
        return view('actuator.create');
    }

    // Store a newly created actuator in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required',
            ]);

        Actuator::create([
            'actuator_name' => $request->name,
            'status' => $request->status,
            ]);

        return redirect()->route('actuator.index')->with('success', 'Actuator created successfully');
    }

    // Show the form for editing the specified actuator
    public function edit($id)
    {
        $actuator = Actuator::findOrFail($id);
        return view('actuator.edit', compact('actuator'));
    }

    // Update the specified actuator in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',            
            'status' => 'required',
            ]);

        $actuator = Actuator::findOrFail($id);
        $actuator->update([
            'actuator_name' => $request->name,
            'status' => $request->status,
            ]);

        return redirect()->route('actuator.index')->with('success', 'Actuator updated successfully');
    }

    // Remove the specified actuator from the database
    public function destroy($id)
    {
        $actuator = Actuator::findOrFail($id);
        $actuator->delete();

        return redirect()->route('actuator.index')->with('success', 'Actuator deleted successfully');
    }
}
