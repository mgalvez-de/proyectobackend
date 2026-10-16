<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Device;
use App\Models\SoftwareFailure;
use App\Models\HardwareFailure;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $softwareFailures = SoftwareFailure::all();
        $hardwareFailures = HardwareFailure::all();

        $problemType = request('problem_type');
        $deviceId = request('device_id');

        return view('tickets.index', compact(
            'softwareFailures',
            'hardwareFailures',
            'problemType',
            'deviceId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => ['required', 'exists:devices,id'],
            'problem_type' => ['required', 'in:software,hardware'],
            'error_description' => ['required', 'string'],
            'severity' => ['required', 'in:low,medium,high,critical'],
            'comments' => ['nullable', 'string'],
        ]);

        $device = Device::findOrFail($validated['device_id']);

        $ticket = Ticket::create([
            'device_id' => $device->id,
            'user_id' => auth()->id(),
            'type' => 'reactive',
            'problem_type' => $validated['problem_type'],
            'error_description' => $validated['error_description'],
            'severity' => $validated['severity'],
            'status' => 'pending',
            'comments' => $validated['comments'] ?? null,
        ]);

        return redirect()
            ->route('departments.history', $device->department_id)
            ->with('success', 'Ticket creado correctamente.');
    }
}
