<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Department;
use App\Models\HardwareComponent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HardwareComponentController extends Controller
{
    public function index()
    {
        $components = HardwareComponent::with(['device.department', 'brand'])->get();

        // dispositivos agrupados por departamento para el selector
        $departments = Department::with(['devices' => fn ($query) => $query->orderBy('name')])->orderBy('id')->get();

        $brands = Brand::orderBy('name')->get();
        $types = HardwareComponent::TYPES;

        return view('hardware-components.index', compact('components', 'departments', 'brands', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        HardwareComponent::create($data);

        return redirect()->route('hardware-components.index')->with('success', 'Componente creado correctamente.');
    }

    public function update(Request $request, HardwareComponent $hardwareComponent)
    {
        $data = $request->validate($this->rules($hardwareComponent), $this->messages());

        $hardwareComponent->update($data);

        return redirect()->route('hardware-components.index')->with('success', 'Componente actualizado correctamente.');
    }

    public function destroy(HardwareComponent $hardwareComponent)
    {
        $hardwareComponent->delete();

        return redirect()->route('hardware-components.index')->with('success', 'Componente eliminado correctamente.');
    }

    private function rules(?HardwareComponent $component = null): array
    {
        return [
            'device_id' => ['required', 'exists:devices,id'],
            'type' => ['required', Rule::in(array_keys(HardwareComponent::TYPES))],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'model' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100', Rule::unique('hardware_components', 'serial_number')->ignore($component?->id)],
            'installed_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:installed_at'],
        ];
    }

    private function messages(): array
    {
        return [
            'device_id.required' => 'Selecciona el dispositivo al que pertenece el componente.',
            'device_id.exists' => 'El dispositivo seleccionado no existe.',
            'type.required' => 'Selecciona el tipo de componente.',
            'type.in' => 'El tipo de componente no es válido.',
            'brand_id.exists' => 'La marca seleccionada no existe.',
            'serial_number.unique' => 'Ya existe un componente con ese número de serie.',
            'installed_at.date' => 'La fecha de instalación no es válida.',
            'expires_at.date' => 'La fecha de vencimiento no es válida.',
            'expires_at.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la de instalación.',
        ];
    }
}
