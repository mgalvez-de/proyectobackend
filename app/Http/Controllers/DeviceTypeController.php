<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{
    public function index()
    {
        $deviceTypes = DeviceType::all();

        return view('device-types.index', compact('deviceTypes'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        DeviceType::create([
            'name' => $request->name
        ]);

        return redirect()->route('device-types.index')->with('success', 'Tipo de dispositivo creado correctamente.');
    }

    public function show(string $id)
    {
        $deviceType = DeviceType::find($id);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, DeviceType $deviceType)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $deviceType->update([
            'name' => $request->name
        ]);

        return redirect()->route('device-types.index');
    }

    public function destroy(string $id)
    {
        DeviceType::find($id)->delete();

        return redirect()->route('device-types.index')->with('success', 'Tipo de dispositivo eliminado correctamente.');
    }
}
