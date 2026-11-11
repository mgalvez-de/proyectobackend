<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ram;
use Illuminate\Http\Request;

class RamController extends Controller
{
    /**
     * GET /api/rams
     * Lista las RAM, de 15 en 15.
     */
    public function index()
    {
        return Ram::paginate(15);
    }

    /**
     * POST /api/rams
     * Crea una RAM.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $ram = Ram::create($data);

        return response()->json([
            'message' => 'RAM creada correctamente.',
            'data' => $ram,
        ], 201);
    }

    /**
     * GET /api/rams/{id}
     * Muestra una RAM.
     */
    public function show(Ram $ram)
    {
        return response()->json([
            'data' => $ram,
        ]);
    }

    /**
     * PUT /api/rams/{id}
     * Edita una RAM.
     */
    public function update(Request $request, Ram $ram)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $ram->update($data);

        return response()->json([
            'message' => 'RAM actualizada correctamente.',
            'data' => $ram,
        ]);
    }

    /**
     * DELETE /api/rams/{id}
     * Elimina una RAM (si ningún dispositivo la está usando).
     */
    public function destroy(Ram $ram)
    {
        $inUse = $ram->devices()->count();

        if ($inUse > 0) {
            return response()->json([
                'message' => "No se puede eliminar: {$inUse} dispositivo(s) usan esta RAM.",
            ], 409);
        }

        $ram->delete();

        return response()->json([
            'message' => 'RAM eliminada correctamente.',
        ]);
    }

    private function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:50'],
            'capacity' => ['required', 'string', 'max:50'],
        ];
    }

    private function messages(): array
    {
        return [
            'type.required' => 'El tipo de RAM es obligatorio. Ej: DDR4',
            'type.max' => 'El tipo no puede tener más de 50 caracteres.',
            'capacity.required' => 'La capacidad es obligatoria. Ej: 16 GB',
            'capacity.max' => 'La capacidad no puede tener más de 50 caracteres.',
        ];
    }
}
