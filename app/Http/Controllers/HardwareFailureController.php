<?php

namespace App\Http\Controllers;

use App\Models\HardwareFailure;
use Illuminate\Http\Request;

class HardwareFailureController extends Controller
{
    public function index()
    {
        $hardwareFailures = HardwareFailure::all();

        return view('hardware-failures.index', compact('hardwareFailures'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        HardwareFailure::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('hardware-failures.index')->with('success', 'Reporte de hardware creado correctamente.');
    }

    public function show(string $id)
    {
        $hardwareFailure = HardwareFailure::find($id);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, HardwareFailure $hardwareFailure)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $hardwareFailure->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('hardware-failures.index');
    }

    public function destroy(string $id)
    {
        HardwareFailure::find($id)->delete();

        return redirect()->route('hardware-failures.index')->with('success', 'Reporte de hardware eliminado correctamente.');
    }
}
