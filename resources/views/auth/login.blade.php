<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-900">

    <!-- Navbar -->
    <header class="bg-red-600 border-b border-red-700">
        <div class="px-8 py-3">
            <h1 class="text-2xl font-semibold text-white">
                UCSC
            </h1>
        </div>
    </header>


    <!-- Contenido -->
    <main class="flex justify-center items-center px-6 py-12">

        <div class="w-full max-w-6xl">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                <!-- Espacio para el logo -->
                <div class="flex justify-center items-center h-96">

                    <!-- logo -->
                    (logo aca)
                </div>


                <!-- Login -->
                <div class="w-full max-w-lg mx-auto">

                    <!-- Título -->
                    <div class="text-center mb-8">

                        <h2 class="text-3xl font-bold">
                            Sistema de gestión de dispositivos
                            tecnológicos de UCSC
                        </h2>

                        <p class="text-xl text-gray-700 mt-5">
                            Por favor, inicia sesión
                        </p>

                    </div>


                    <!-- Formulario -->
                    <div class="border border-gray-700 rounded shadow">

                        <!-- Título formulario -->
                        <div class="bg-red-600 text-white text-center py-3">
                            <h3 class="text-xl font-semibold">
                                Ingrese sus credenciales
                            </h3>
                        </div>


                        <div class="p-6">

                            <form method="POST" action="{{ route('login') }}">

                                @csrf

                                <!-- RUT -->
                                <div class="mb-5">

                                    <label for="email" class="block text-lg font-medium mb-2">
                                        Rut (sin puntos ni coma)
                                    </label>

                                    <input id="email" type="text" name="email" value="{{ old('email') }}"
                                        required autofocus autocomplete="username"
                                        class="w-full border border-gray-700 rounded px-3 py-2 text-lg focus:outline-none focus:border-red-600">

                                    @error('email')
                                        <p class="text-red-600 text-sm mt-1">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                <!-- Contraseña -->
                                <div class="mb-6">

                                    <label for="password" class="block text-lg font-medium mb-2">
                                        Contraseña
                                    </label>

                                    <input id="password" type="password" name="password" required
                                        autocomplete="current-password"
                                        class="w-full border border-gray-700 rounded px-3 py-2 text-lg focus:outline-none focus:border-red-600">

                                    @error('password')
                                        <p class="text-red-600 text-sm mt-1">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                <!-- Botón -->
                                <div class="flex justify-center">

                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-semibold text-lg px-6 py-2 rounded">
                                        Iniciar sesión
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

</body>

</html>
