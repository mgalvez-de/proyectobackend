<?php

namespace App\Http\Controllers;

use App\Models\SoftwareFailure;
use Illuminate\Http\Request;

class SoftwareFailureController extends Controller
{
    public function index()
    {
        $softwareFailures = SoftwareFailure::all();

        return view('software-failures.index', compact('softwareFailures'));
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

        SoftwareFailure::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('software-failures.index')->with('success', 'Reporte de software creado correctamente.');
    }

    public function show(string $id)
    {
        $softwareFailure = SoftwareFailure::find($id);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, SoftwareFailure $softwareFailure)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $softwareFailure->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('software-failures.index');
    }

    public function destroy(string $id)
    {
        SoftwareFailure::find($id)->delete();

        return redirect()->route('software-failures.index')->with('success', 'Reporte de software eliminado correctamente.');
    }
}
