<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Processor;
use Illuminate\Http\Request;

/**
 * API REST PÚBLICA de procesadores.
 *
 * No usa middleware: cualquiera puede consumirla.
 * Mismo patrón que Api\RamController.
 */
class ProcessorController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/processors
     */
    public function index()
    {
        return Processor::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/processors
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
        ]);

        $processor = Processor::create($validated);

        return response()->json($processor, 201);
    }

    /**
     * Display the specified resource.
     * GET /api/processors/{processor}
     */
    public function show(Processor $processor)
    {
        return response()->json($processor);
    }

    /**
     * Update the specified resource in storage.
     * PUT|PATCH /api/processors/{processor}
     */
    public function update(Request $request, Processor $processor)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
        ]);

        $processor->update($validated);

        return response()->json($processor, 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/processors/{processor}
     */
    public function destroy(Processor $processor)
    {
        $processor->delete();

        return response()->json(null, 204);
    }
}
