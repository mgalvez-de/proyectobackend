<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · Gestión UCSC</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ucsc.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50">

    <div class="min-h-screen grid lg:grid-cols-2">

        <!-- PANEL IZQUIERDO: identidad UCSC (solo en pantallas grandes) -->
        <div class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-gradient-to-br from-ucsc to-ucsc-dark p-12 text-white">

            <!-- foto del Campus San Andrés con capa roja encima -->
            <img src="{{ asset('images/campus-san-andres.jpg') }}" alt=""
                class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-ucsc/90 via-ucsc/80 to-ucsc-dark/95"></div>

            <div class="relative flex items-center gap-3">
                <img src="{{ asset('images/logo-ucsc.png') }}" alt="UCSC" class="h-12 w-12">
                <div class="leading-tight">
                    <p class="text-lg font-semibold">UCSC</p>
                    <p class="text-xs text-red-100">Universidad Católica de la Santísima Concepción</p>
                </div>
            </div>

            <div class="relative max-w-lg">
                <h1 class="text-4xl xl:text-5xl font-bold leading-tight">
                    Sistema de Gestión de Dispositivos Tecnológicos
                </h1>
                <p class="mt-4 text-lg text-red-100">
                    Controla el inventario, los tickets de soporte y el historial de los equipos de cada departamento.
                </p>

                <ul class="mt-10 space-y-4">
                    <li class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/15">
                            <x-icon name="computer" class="w-5 h-5" />
                        </span>
                        Inventario de dispositivos por departamento
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/15">
                            <x-icon name="ticket" class="w-5 h-5" />
                        </span>
                        Tickets de soporte de hardware y software
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/15">
                            <x-icon name="clock" class="w-5 h-5" />
                        </span>
                        Historial y trazabilidad de cada equipo
                    </li>
                </ul>
            </div>

            <div class="relative space-y-1 text-sm text-red-100">
                <p>&copy; {{ date('Y') }} Universidad Católica de la Santísima Concepción</p>
            </div>
        </div>

        <!-- PANEL DERECHO: formulario -->
        <div class="flex items-center justify-center px-4 py-12 sm:px-8">
            <div class="w-full max-w-md">

                <img src="{{ asset('images/ucsc-logo-horizontal.png') }}" alt="UCSC" class="h-16 mx-auto">

                <div class="mt-8 text-center">
                    <h2 class="text-2xl font-bold text-gray-800">Iniciar sesión</h2>
                    <p class="mt-1 text-gray-500">Ingresa con tu RUT y contraseña para acceder al sistema.</p>
                </div>

                <div class="mt-8 bg-white rounded-2xl shadow-xl ring-1 ring-gray-900/5 p-6 sm:p-8">

                    @if ($errors->any())
                        <div class="mb-5 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-ucsc"
                            role="alert">
                            <x-icon name="exclamation" class="w-5 h-5 shrink-0" />
                            <p>{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5"
                        x-data="{ loading: false }" @submit="loading = true">
                        @csrf

                        <!-- RUT -->
                        <div>
                            <label for="rut" class="block text-sm font-semibold text-gray-700 mb-1.5">RUT</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <x-icon name="identification" class="w-5 h-5" />
                                </span>
                                <input id="rut" type="text" name="rut" value="{{ old('rut') }}"
                                    placeholder="Ej: 12345678K" maxlength="9"
                                    required autofocus autocomplete="username"
                                    class="w-full rounded-lg border-gray-300 pl-10 py-2.5 shadow-sm focus:border-ucsc focus:ring-ucsc @error('rut') border-red-400 @enderror">
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Sin puntos ni guion.</p>
                        </div>

                        <!-- Contraseña -->
                        <div x-data="{ show: false }">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Contraseña</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <x-icon name="lock" class="w-5 h-5" />
                                </span>
                                <input id="password" type="password" name="password" x-bind:type="show ? 'text' : 'password'"
                                    placeholder="Ingresa tu contraseña"
                                    required autocomplete="current-password"
                                    class="w-full rounded-lg border-gray-300 pl-10 pr-11 py-2.5 shadow-sm focus:border-ucsc focus:ring-ucsc @error('password') border-red-400 @enderror">
                                <button type="button" @click="show = ! show"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600"
                                    x-bind:title="show ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                                    <x-icon name="eye" class="w-5 h-5" x-show="! show" />
                                    <x-icon name="eye-slash" class="w-5 h-5" x-show="show" x-cloak />
                                </button>
                            </div>
                        </div>

                        <!-- Recordarme -->
                        <label for="remember" class="flex items-center gap-2 text-sm text-gray-600">
                            <input id="remember" type="checkbox" name="remember"
                                class="rounded border-gray-300 text-ucsc focus:ring-ucsc">
                            Mantener la sesión iniciada
                        </label>

                        <x-button type="submit" size="lg" class="w-full" x-bind:disabled="loading">
                            <svg x-show="loading" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span x-text="loading ? 'Ingresando...' : 'Iniciar sesión'">Iniciar sesión</span>
                        </x-button>
                    </form>
                </div>

                <p class="mt-6 text-center text-sm text-gray-500">
                    ¿Problemas para ingresar? Contacta al administrador del sistema.
                </p>

            </div>
        </div>

    </div>

</body>

</html>
