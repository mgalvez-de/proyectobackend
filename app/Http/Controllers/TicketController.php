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

        $device = $deviceId
            ? Device::with([
                'brand',
                'deviceType',
                'processor',
                'storage',
                'ram',
                'department'
            ])->find($deviceId)
            : null;

        return view('tickets.index', compact(
            'softwareFailures',
            'hardwareFailures',
            'problemType',
            'deviceId',
            'device'
        ));
    }

    public function create(Request $request)
    {
        $softwareFailures = SoftwareFailure::all();
        $hardwareFailures = HardwareFailure::all();

        $problemType = $request->query('problem_type');
        $deviceId = $request->query('device_id');

        $device = $deviceId
            ? Device::with([
                'brand',
                'deviceType',
                'processor',
                'storage',
                'ram',
                'department'
            ])->find($deviceId)
            : null;

        return view('tickets.index', compact(
            'softwareFailures',
            'hardwareFailures',
            'problemType',
            'deviceId',
            'device'
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

        Ticket::create([
            'device_id' => $device->id,
            'user_id' => auth()->id(),
            'type' => 'reactive',
            'problem_type' => $validated['problem_type'],
            'error_description' => $validated['error_description'],
            'severity' => $validated['severity'],
            'status' => 'pending',
            'comments' => $validated['comments'] ?? null,
        ]);

        $mensaje = 'Ticket creado correctamente.';

        // el estudiante ve el ticket en "Mis reportes"; el resto, en el historial del departamento
        if (auth()->user()->rol === 'estudiante') {
            return redirect()
                ->route('my-reports.index')
                ->with('success', $mensaje);
        }

        if (auth()->user()->rol === 'tecnico') {
            return redirect()
                ->route('my-tickets.index')
                ->with('success', $mensaje);
        }

        return redirect()
            ->route('departments.history', $device->department_id)
            ->with('success', $mensaje);
    }

    // Cierre desde el historial (admin o supervisor): siempre pide la solución aplicada
    public function close(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->type === 'reactive', 404);

        abort_unless(
            auth()->user()->canAccessDepartment($ticket->device->department),
            403,
            'No tienes acceso a este departamento.'
        );

        $data = $request->validate([
            'solution' => ['required', 'string', 'max:2000'],
        ], [
            'solution.required' => 'Para cerrar el ticket debes escribir la solución aplicada.',
        ]);

        $ticket->update([
            'status' => 'closed',
            'solution' => $data['solution'],
            'closed_at' => now(),
            'closed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Ticket cerrado correctamente.');
    }
}
