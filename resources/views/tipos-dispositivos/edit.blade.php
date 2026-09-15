<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar tipo de dispositivo
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <form action="{{ route('tipos-dispositivos.update', $tipos_dispositivo) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-sm text-gray-700">
                            Tipo de dispositivo
                        </label>

                        <input
                            type="text"
                            name="tipo"
                            value="{{ old('tipo', $tipos_dispositivo->tipo) }}"
                            class="mt-1 block w-full rounded-md border-gray-300"
                        >

                        @error('tipo')
                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mt-6 flex gap-3">

                        <a href="{{ route('tipos-dispositivos.index') }}"
                           class="px-4 py-2 bg-gray-200 rounded-md">
                            Cancelar
                        </a>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Guardar cambios
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
