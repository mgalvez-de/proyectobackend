
<x-app-layout>

    <x-navbar />

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @if (auth()->user()->rol === 'admin')
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700"></a>
        @endif

        <div class="mt-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            <div class="bg-[#D12421] text-white px-6 py-4">
                <h1 class="text-xl font-semibold">
                    ¿Qué sucedió?
                </h1>
                <p class="text-sm text-gray-200 mt-1">
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
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                        <p class="font-semibold">No se pudo crear el ticket.</p>
                        <ul class="mt-2 list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
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
                                class="hidden w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">

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
                                class="hidden w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">

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
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">

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
                            class="w-full min-h-48 rounded-lg border-gray-300 shadow-sm resize-y focus:border-red-500 focus:ring-red-500">{{ old('comments') }}</textarea>
                    </div>

                </div>

                <!-- botones -->
                <div class="mt-8 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-gray-200 pt-6">

                    <a
                        href="{{ url()->previous() }}"
                        class="inline-flex justify-center items-center rounded-lg bg-gray-600 px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-gray-700 transition">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="inline-flex justify-center items-center rounded-lg bg-[#D12421] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                        Generar ticket
                    </button>

                </div>

            </form>
        </div>
    </div>

    <script>
        const softwareSelect = document.getElementById('softwareSelect');
        const hardwareSelect = document.getElementById('hardwareSelect');
        const problemType = document.getElementById('problem_type');

        function mostrarSoftware() {
            softwareSelect.classList.remove('hidden');
            hardwareSelect.classList.add('hidden');

            softwareSelect.name = 'error_description';
            hardwareSelect.removeAttribute('name');

            softwareSelect.required = true;
            hardwareSelect.required = false;

            problemType.value = 'software';

            document.getElementById('btnSoftware').className =
                'rounded-lg border-2 border-red-600 bg-red-50 px-5 py-2 font-medium text-red-700';

            document.getElementById('btnHardware').className =
                'rounded-lg border-2 border-gray-300 px-5 py-2 font-medium text-gray-700';
        }

        function mostrarHardware() {
            hardwareSelect.classList.remove('hidden');
            softwareSelect.classList.add('hidden');

            hardwareSelect.name = 'error_description';
            softwareSelect.removeAttribute('name');

            hardwareSelect.required = true;
            softwareSelect.required = false;

            problemType.value = 'hardware';

            document.getElementById('btnHardware').className =
                'rounded-lg border-2 border-red-600 bg-red-50 px-5 py-2 font-medium text-red-700';

            document.getElementById('btnSoftware').className =
                'rounded-lg border-2 border-gray-300 px-5 py-2 font-medium text-gray-700';
        }

        <!-- mantener la selección anterior si laravel devuelve errores -->
        if (problemType.value === 'hardware') {
            mostrarHardware();
        } else {
            mostrarSoftware();
        }
    </script>

</x-app-layout>
