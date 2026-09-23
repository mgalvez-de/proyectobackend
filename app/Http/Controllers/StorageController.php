<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;

class StorageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $storages = Storage::all();
        return view("storages.index", compact("storages"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'capacity' => 'required',
        ]);

        Storage::create([
            'type' => $request->type,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('storages.index')->with('success', 'Almacenamiento creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $storage = Storage::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Storage $storage)
    {
        $request->validate([
            'type' => 'required',
            'capacity' => 'required',
        ]);

        $storage->update([
            'type' => $request->type,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('storages.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Storage::find($id)->delete();

        return redirect()->route('storages.index')->with('success', 'Almacenamiento eliminado correctamente.');
    }
}
