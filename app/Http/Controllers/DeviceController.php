<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Brand;
use App\Models\Storage;
use App\Models\Ram;
use App\Models\Department;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $department = Department::where('name', 'Informática')->firstOrFail();

        $devices = Device::with(['brand', 'storage', 'ram', 'department'])
            ->where('department_id', $department->id)
            ->get();

        $brands = Brand::all();
        $storages = Storage::all();
        $rams = Ram::all();

        return view('informatica.index', compact(
            'devices',
            'brands',
            'storages',
            'rams',
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
            'department_id' => 'required|exists:departments,id'
        ]);

        Device::create([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'storage_id' => $request->storage_id,
            'ram_id' => $request->ram_id,
            'department_id' => $request->department_id
        ]);

        return redirect()->route('informatica.index');
    }

    public function update(Request $request, Device $device)
    {
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required|exists:brands,id',
            'storage_id' => 'required|exists:storages,id',
            'ram_id' => 'required|exists:rams,id',
            'department_id' => 'required|exists:departments,id'
        ]);

        $device->update([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'storage_id' => $request->storage_id,
            'ram_id' => $request->ram_id,
            'department_id' => $request->department_id
        ]);

        return redirect()->route('informatica.index');
    }

    public function destroy(string $id)
    {
        Device::find($id)->delete();

        return redirect()->route('informatica.index');
    }
}
