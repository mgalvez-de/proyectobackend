<?php

namespace App\Http\Controllers;

use App\Models\Ram;
use Illuminate\Http\Request;

class RamController extends Controller
{
    public function index()
    {
        $rams = Ram::all();

        return view('rams.index', compact('rams'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'capacity' => 'required'
        ]);

        Ram::create([
            'type' => $request->type,
            'capacity' => $request->capacity
        ]);

        return redirect()->route('rams.index');
    }

    public function show(string $id)
    {
        $ram = Ram::find($id);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Ram $ram)
    {
        $request->validate([
            'type' => 'required',
            'capacity' => 'required'
        ]);

        $ram->update([
            'type' => $request->type,
            'capacity' => $request->capacity
        ]);

        return redirect()->route('rams.index');
    }

    public function destroy(string $id)
    {
        Ram::find($id)->delete();

        return redirect()->route('rams.index');
    }
}
