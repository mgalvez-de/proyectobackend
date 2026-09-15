<?php

namespace App\Http\Controllers;

use App\Models\TipoDisp;
use Illuminate\Http\Request;

class TipoDispController extends Controller
{
    public function index()
    {
        $tipos = TipoDisp::all();

        return view('tipos-dispositivos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos-dispositivos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|max:50',
        ]);

        TipoDisp::create([
            'tipo' => $request->tipo,
        ]);

        return redirect()
            ->route('tipos-dispositivos.index')
            ->with('success', 'Tipo de dispositivo creado correctamente.');
    }

    public function edit(TipoDisp $tipos_dispositivo)
    {
        return view('tipos-dispositivos.edit', compact('tipos_dispositivo'));
    }

    public function update(Request $request, TipoDisp $tipos_dispositivo)
    {
        $request->validate([
            'tipo' => 'required|max:50',
        ]);

        $tipos_dispositivo->update([
            'tipo' => $request->tipo,
        ]);

        return redirect()
            ->route('tipos-dispositivos.index')
            ->with('success', 'Tipo de dispositivo actualizado correctamente.');
    }

    public function destroy(TipoDisp $tipos_dispositivo)
    {
        $tipos_dispositivo->delete();

        return redirect()
            ->route('tipos-dispositivos.index')
            ->with('success', 'Tipo de dispositivo eliminado correctamente.');
    }
}
