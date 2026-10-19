<?php

namespace App\Http\Controllers;

use App\Models\Processor;
use App\Models\DeviceType;
use App\Models\Device;
use App\Models\Brand;
use App\Models\Storage;
use App\Models\Ram;
use App\Models\Department;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Department $department)
    {
        $this->authorizeDepartment($department);

        $devices = Device::with([
            'brand',
            'storage',
            'ram',
            'department',
            'processor',
            'deviceType'
        ])
            ->where('department_id', $department->id)
            ->get();

        $brands = Brand::all();
        $storages = Storage::all();
        $rams = Ram::all();
        $processors = Processor::all();
        $deviceTypes = DeviceType::all();

        return view('departments.show', compact(
            'devices',
            'brands',
            'storages',
            'rams',
            'processors',
            'deviceTypes',
            'department'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required|exists:brands,id',
            'storage_id' => 'required|exists:storages,id',
            'ram_id' => 'required|exists:rams,id',
            'processor_id' => 'required|exists:processors,id',
            'device_type_id' => 'required|exists:device_types,id',
            'department_id' => 'required|exists:departments,id'
        ]);

        $department = Department::findOrFail($request->department_id);

        $this->authorizeDepartment($department);

        Device::create([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'storage_id' => $request->storage_id,
            'ram_id' => $request->ram_id,
            'processor_id' => $request->processor_id,
            'device_type_id' => $request->device_type_id,
            'department_id' => $department->id
        ]);

        return redirect()
            ->route('departments.show', $department)
            ->with('success', 'Dispositivo creado correctamente.');
    }

    public function update(Request $request, Device $device)
    {
        $this->authorizeDepartment($device->department);

        $request->validate([
            'name' => 'required',
            'brand_id' => 'required|exists:brands,id',
            'storage_id' => 'required|exists:storages,id',
            'ram_id' => 'required|exists:rams,id',
            'processor_id' => 'required|exists:processors,id',
            'device_type_id' => 'required|exists:device_types,id'
        ]);

        $device->update([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'storage_id' => $request->storage_id,
            'ram_id' => $request->ram_id,
            'processor_id' => $request->processor_id,
            'device_type_id' => $request->device_type_id
        ]);

        return redirect()
            ->route('departments.show', $device->department_id)
            ->with('success', 'Dispositivo actualizado correctamente.');
    }

    public function destroy(Device $device)
    {
        $this->authorizeDepartment($device->department);

        $departmentId = $device->department_id;

        $device->delete();

        return redirect()
            ->route('departments.show', $departmentId)
            ->with('success', 'Dispositivo eliminado correctamente.');
    }

    private function authorizeDepartment(Department $department): void
    {
        abort_unless(
            auth()->user()->canAccessDepartment($department),
            403,
            'No tienes acceso a este departamento.'
        );
    }
}
