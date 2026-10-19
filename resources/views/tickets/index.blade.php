<x-app-layout title="Nuevo ticket">

    <x-navbar />

    @php
        $breadcrumbs = auth()->user()->rol === 'admin' ? ['Inicio' => route('dashboard')] : [];

        if ($device?->department) {
            $breadcrumbs[$device->department->displayName()] = route('departments.show', $device->department);
        }

        $breadcrumbs['Nuevo ticket'] = null;
    @endphp

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @if (count($breadcrumbs) > 1)
            <x-breadcrumbs :items="$breadcrumbs" />
        @endif

        <!-- ficha del dispositivo -->
        @if ($device)
            <div
                class="mt-4 bg-white border border-slate-200 rounded-xl shadow-sm p-5 flex flex-col md:flex-row md:items-center gap-5">

                <div class="flex items-center gap-4 md:flex-1">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-ucsc/10 text-ucsc">
                        <x-icon name="computer" class="w-8 h-8" />
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Dispositivo seleccionado
                        </p>
                        <h2 class="text-xl font-bold text-slate-800">
                            {{ $device->name }}
                        </h2>
                        <p class="text-sm text-slate-500">
                            {{ $device->deviceType?->name ?? 'Sin tipo' }}
                            @if ($device->department)
                                · {{ $device->department->displayName() }}
                            @endif
                        </p>
                    </div>
                </div>

                <dl class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-3 text-sm md:border-l md:border-slate-200 md:pl-6">
                    <div>
                        <dt class="text-xs text-slate-400">Marca</dt>
                        <dd class="font-medium text-slate-700">{{ $device->brand?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Procesador</dt>
                        <dd class="font-medium text-slate-700">
                            {{ $device->processor ? $device->processor->brand . ' ' . $device->processor->model : '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">RAM</dt>
                        <dd class="font-medium text-slate-700">
                            {{ $device->ram ? $device->ram->type . ' · ' . $device->ram->capacity : '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Almacenamiento</dt>
                        <dd class="font-medium text-slate-700">
                            {{ $device->storage ? $device->storage->type . ' · ' . $device->storage->capacity : '—' }}
                        </dd>
                    </div>
                </dl>

            </div>
        @else
            <div class="mt-4 flex items-start gap-3 rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-yellow-800">
                <x-icon name="exclamation" class="w-5 h-5 shrink-0 mt-0.5" />
                <p class="text-sm">
                    No se seleccionó ningún dispositivo. Abre el detalle de un dispositivo y usa
                    <strong>«Crear ticket para este dispositivo»</strong>.
                </p>
            </div>
        @endif

        <div class="mt-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            <div class="bg-ucsc text-white px-6 py-4">
                <h1 class="text-xl font-semibold">
                    ¿Qué sucedió?
                </h1>
                <p class="text-sm text-red-100 mt-1">
                    Describe el problema del dispositivo para generar un ticket.
                </p>
            </div>

            <!-- formulario para crear el ticket -->
            <form action="{{ route('tickets.store') }}" method="POST" class="p-6 md:p-8">
                @csrf

                <input type="hidden" name="device_id" value="{{ old('device_id', $deviceId) }}">
                <input type="hidden" name="problem_type" id="problem_type" value="{{ old('problem_type', 'software') }}">

                <!-- errores generales -->
                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                        <x-icon name="exclamation" class="w-5 h-5 shrink-0 mt-0.5" />
                        <div>
                            <p class="font-semibold">No se pudo crear el ticket.</p>
                            <ul class="mt-2 list-disc pl-5 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- columna izquierda -->
                    <div class="space-y-7">

                        <!-- tipo de problema -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-3">
                                Tipo de problema
                            </label>

                            <div class="flex gap-3">
                                <button
                                    type="button"
                                    id="btnSoftware"
                                    onclick="mostrarSoftware()"
                                    class="rounded-lg border-2 px-5 py-2 font-medium">
                                    Software
                                </button>

                                <button
                                    type="button"
                                    id="btnHardware"
                                    onclick="mostrarHardware()"
                                    class="rounded-lg border-2 px-5 py-2 font-medium">
                                    Hardware
                                </button>
                            </div>
                        </div>

                        <!-- tipo de falla -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-3">
                                ¿Qué falló?
                            </label>

                            <!-- fallas de software -->
                            <select
                                id="softwareSelect"
                                class="hidden w-full rounded-lg border-gray-300 shadow-sm focus:border-ucsc focus:ring-ucsc">

                                <option value="">Selecciona una falla de software</option>

                                @foreach ($softwareFailures as $failure)
                                    <option
                                        value="{{ $failure->name }}"
                                        @selected(old('problem_type', 'software') === 'software' && old('error_description') === $failure->name)>
                                        {{ $failure->name }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- fallas de hardware -->
                            <select
                                id="hardwareSelect"
                                class="hidden w-full rounded-lg border-gray-300 shadow-sm focus:border-ucsc focus:ring-ucsc">

                                <option value="">Selecciona una falla de hardware</option>

                                @foreach ($hardwareFailures as $failure)
                                    <option
                                        value="{{ $failure->name }}"
                                        @selected(old('problem_type') === 'hardware' && old('error_description') === $failure->name)>
                                        {{ $failure->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- nivel de gravedad -->
                        <div>
                            <label for="severity" class="block text-base font-semibold text-gray-800 mb-3">
                                Nivel de gravedad
                            </label>

                            <select
                                id="severity"
                                name="severity"
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-ucsc focus:ring-ucsc">

                                <option value="">Selecciona la gravedad</option>
                                <option value="low" @selected(old('severity') === 'low')>Baja</option>
                                <option value="medium" @selected(old('severity') === 'medium')>Media</option>
                                <option value="high" @selected(old('severity') === 'high')>Alta</option>
                                <option value="critical" @selected(old('severity') === 'critical')>Crítica</option>
                            </select>
                        </div>

                    </div>

                    <!-- columna derecha: comentarios -->
                    <div>
                        <label for="comments" class="block text-base font-semibold text-gray-800 mb-3">
                            Sección de comentarios
                        </label>

                        <textarea
                            id="comments"
                            name="comments"
                            rows="8"
                            placeholder="Describe con más detalle qué ocurrió..."
                            class="w-full min-h-48 rounded-lg border-gray-300 shadow-sm resize-y focus:border-ucsc focus:ring-ucsc">{{ old('comments') }}</textarea>
                    </div>

                </div>

                <!-- botones -->
                <div class="mt-8 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-gray-200 pt-6">

                    <x-button :href="url()->previous()" variant="secondary" size="lg">
                        Cancelar
                    </x-button>

                    <x-button type="submit" size="lg">
                        <x-icon name="ticket" class="w-5 h-5" />
                        Generar ticket
                    </x-button>

                </div>

            </form>
        </div>
    </div>

    <script>
        const softwareSelect = document.getElementById('softwareSelect');
        const hardwareSelect = document.getElementById('hardwareSelect');
        const problemType = document.getElementById('problem_type');

        const selectedClass = 'rounded-lg border-2 border-ucsc bg-ucsc/5 px-5 py-2 font-medium text-ucsc';
        const unselectedClass = 'rounded-lg border-2 border-gray-300 px-5 py-2 font-medium text-gray-700 hover:border-gray-400';

        function mostrarSoftware() {
            softwareSelect.classList.remove('hidden');
            hardwareSelect.classList.add('hidden');

            softwareSelect.name = 'error_description';
            hardwareSelect.removeAttribute('name');

            softwareSelect.required = true;
            hardwareSelect.required = false;

            problemType.value = 'software';

            document.getElementById('btnSoftware').className = selectedClass;
            document.getElementById('btnHardware').className = unselectedClass;
        }

        function mostrarHardware() {
            hardwareSelect.classList.remove('hidden');
            softwareSelect.classList.add('hidden');

            hardwareSelect.name = 'error_description';
            softwareSelect.removeAttribute('name');

            hardwareSelect.required = true;
            softwareSelect.required = false;

            problemType.value = 'hardware';

            document.getElementById('btnHardware').className = selectedClass;
            document.getElementById('btnSoftware').className = unselectedClass;
        }

        // mantener la selección anterior si laravel devuelve errores
        if (problemType.value === 'hardware') {
            mostrarHardware();
        } else {
            mostrarSoftware();
        }
    </script>

</x-app-layout>
