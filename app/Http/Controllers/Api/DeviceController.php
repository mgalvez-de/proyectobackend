<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Device;
use Illuminate\Http\Request;

/**
 * API REST PRIVADA de equipos (devices).
 *
 * Todas las rutas que apuntan a este controlador viven dentro del grupo
 * Route::middleware('auth:sanctum') de routes/api.php, por lo tanto exigen
 * un token válido en el header:  Authorization: Bearer {token}
 *
 * Además se respeta la misma regla de negocio del sistema web:
 * un supervisor sólo ve/edita los equipos de su propio departamento,
 * el admin puede con todos.
 */
class DeviceController extends Controller
{
    private const RELATIONS = [
        'brand',
        'storage',
        'ram',
        'processor',
        'deviceType',
        'department',
    ];

    /**
     * GET /api/devices
     * GET /api/devices?department_id=3
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Device::with(self::RELATIONS);

        // Si no es admin, sólo su departamento.
        if ($user->rol !== 'admin') {
            $query->where('department_id', $user->department_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return $query->orderBy('id')->paginate(15);
    }

    /**
     * POST /api/devices
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'brand_id'       => 'required|exists:brands,id',
            'storage_id'     => 'required|exists:storages,id',
            'ram_id'         => 'required|exists:rams,id',
            'processor_id'   => 'required|exists:processors,id',
            'device_type_id' => 'required|exists:device_types,id',
            'department_id'  => 'required|exists:departments,id',
        ]);

        $department = Department::findOrFail($validated['department_id']);

        $this->authorizeDepartment($request, $department);

        $device = Device::create($validated);

        return response()->json($device->load(self::RELATIONS), 201);
    }

    /**
     * GET /api/devices/{device}
     */
    public function show(Request $request, Device $device)
    {
        $this->authorizeDepartment($request, $device->department);

        return response()->json($device->load(self::RELATIONS));
    }

    /**
     * PUT|PATCH /api/devices/{device}
     */
    public function update(Request $request, Device $device)
    {
        $this->authorizeDepartment($request, $device->department);

        $validated = $request->validate([
            'name'           => 'sometimes|required|string|max:255',
            'brand_id'       => 'sometimes|required|exists:brands,id',
            'storage_id'     => 'sometimes|required|exists:storages,id',
            'ram_id'         => 'sometimes|required|exists:rams,id',
            'processor_id'   => 'sometimes|required|exists:processors,id',
            'device_type_id' => 'sometimes|required|exists:device_types,id',
        ]);

        $device->update($validated);

        return response()->json($device->load(self::RELATIONS), 200);
    }

    /**
     * DELETE /api/devices/{device}
     * Esta ruta está además dentro del middleware 'role:admin'.
     */
    public function destroy(Device $device)
    {
        $device->delete();

        return response()->json(null, 204);
    }

    /**
     * GET /api/stats  (sólo admin)
     * Pequeño resumen del inventario.
     */
    public function stats()
    {
        return response()->json([
            'total_equipos'        => Device::count(),
            'equipos_por_depto'    => Device::selectRaw('department_id, count(*) as total')
                ->groupBy('department_id')
                ->pluck('total', 'department_id'),
            'equipos_por_procesador' => Device::selectRaw('processor_id, count(*) as total')
                ->groupBy('processor_id')
                ->pluck('total', 'processor_id'),
        ]);
    }

    /**
     * Reutiliza la regla del modelo User: admin puede con todo,
     * supervisor sólo con su departamento.
     */
    private function authorizeDepartment(Request $request, Department $department): void
    {
        abort_unless(
            $request->user()->canAccessDepartment($department),
            403,
            'No tienes acceso a este departamento.'
        );
    }
}
