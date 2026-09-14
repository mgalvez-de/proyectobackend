<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página de Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8f9fa] text-gray-900">

    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-5xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

                <div class="mb-4 md:mb-0 flex justify-center">
                    <img src="{{ asset('images/ucsc-logo-horizontal.png') }}" alt="UCSC" class="w-full max-w-md">
                </div>

                <div class="w-full">
                    <h2 class="text-2xl font-bold text-center mb-3">
                        Sistema de Gestión de dispositivos Tecnológicos
                        Universidad Católica de la Santísima Concepción
                    </h2>
                    <p class="text-center text-gray-600 mb-3">Por favor, inicia sesión</p>

                    @if ($errors->any())
                        <div class="mb-3 text-center bg-red-50 border border-[#D12421] text-[#D12421] rounded shadow-sm px-4 py-2">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <x-card variant="danger">
                        <x-slot name="header">Ingrese sus credenciales</x-slot>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="rut" class="block text-gray-600 mb-1">
                                    Rut (sin puntos ni coma)
                                </label>
                                <input id="rut" type="text" name="rut" value="{{ old('rut') }}"
                                    placeholder="Ej: 22220848"
                                    required autofocus autocomplete="username"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-[#D12421] focus:ring-1 focus:ring-[#D12421]">
                                @error('rut')
                                    <p class="text-[#D12421] text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-gray-600 mb-1">Contraseña</label>
                                <input id="password" type="password" name="password"
                                    placeholder="Ej: ab12345"
                                    required autocomplete="current-password"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-[#D12421] focus:ring-1 focus:ring-[#D12421]">
                                @error('password')
                                    <p class="text-[#D12421] text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="text-center">
                                <x-button type="submit" variant="danger" size="lg" class="w-full md:w-auto">
                                    Iniciar sesión
                                </x-button>
                            </div>
                        </form>
                    </x-card>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
