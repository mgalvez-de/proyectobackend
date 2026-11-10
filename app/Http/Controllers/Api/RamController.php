<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ram;
use Illuminate\Http\Request;

class RamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Ram::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'capacity' => 'required'
        ]);

        $ram = Ram::create($validated);

        return response()->json($ram, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ram $ram)
    {
        return response()->json($ram);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ram $ram)
    {
        $validated = $request->validate([
            'type' => 'required',
            'capacity' => 'required'
        ]);

        $ram->update($validated);

        return response()->json($ram, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ram $ram)
    {
        $ram->delete();

        return response()->json(null, 204);
    }
}
