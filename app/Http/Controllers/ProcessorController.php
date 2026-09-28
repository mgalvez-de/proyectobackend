<?php

namespace App\Http\Controllers;

use App\Models\Processor;
use Illuminate\Http\Request;

class ProcessorController extends Controller
{
    public function index()
    {
        $processors = Processor::all();

        return view('processors.index', compact('processors'));
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

        Processor::create([
            'brand' => $request->brand,
            'model' => $request->model
        ]);

        return redirect()->route('processors.index')->with('success', 'Procesador creado correctamente.');
    }

    public function show(string $id)
    {
        $processor = Processor::find($id);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Processor $processor)
    {
        $request->validate([
            'brand' => 'required',
            'model' => 'required'
        ]);

        $processor->update([
            'brand' => $request->brand,
            'model' => $request->model
        ]);

        return redirect()->route('processors.index');
    }

    public function destroy(string $id)
    {
        Processor::find($id)->delete();

        return redirect()->route('processors.index')->with('success', 'Procesador eliminado correctamente.');
    }
}
