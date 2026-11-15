<?php

namespace App\Http\Controllers;

use App\Models\Motherboard;
use Illuminate\Http\Request;

class MotherboardController extends Controller
{
    public function index()
    {
        $motherboards = Motherboard::all();

        return view('motherboards.index', compact('motherboards'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required',
            'model' => 'required'
        ]);

        Motherboard::create([
            'brand' => $request->brand,
            'model' => $request->model
        ]);

        return redirect()->route('motherboards.index')->with('success', 'Placa madre creada correctamente.');
    }

    public function show(string $id)
    {
        $motherboard = Motherboard::find($id);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Motherboard $motherboard)
    {
        $request->validate([
            'brand' => 'required',
            'model' => 'required'
        ]);

        $motherboard->update([
            'brand' => $request->brand,
            'model' => $request->model
        ]);

        return redirect()->route('motherboards.index')->with('success', 'Placa madre actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        Motherboard::find($id)->delete();

        return redirect()->route('motherboards.index')->with('success', 'Placa madre eliminada correctamente.');
    }
}
