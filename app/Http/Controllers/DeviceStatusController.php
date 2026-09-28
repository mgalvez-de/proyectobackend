<?php

namespace App\Http\Controllers;

use App\Models\DeviceStatus;
use Illuminate\Http\Request;

class DeviceStatusController extends Controller
{
    public function index()
    {
        $deviceStatuses = DeviceStatus::all();

        return view('device-statuses.index', compact('deviceStatuses'));
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

        DeviceStatus::create([
            'name' => $request->name
        ]);

        return redirect()->route('device-statuses.index')->with('success', 'Estado de equipo creado correctamente.');
    }

    public function show(string $id)
    {
        $deviceStatus = DeviceStatus::find($id);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, DeviceStatus $deviceStatus)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $deviceStatus->update([
            'name' => $request->name
        ]);

        return redirect()->route('device-statuses.index');
    }

    public function destroy(string $id)
    {
        DeviceStatus::find($id)->delete();

        return redirect()->route('device-statuses.index')->with('success', 'Estado de equipo eliminado correctamente.');
    }
}
