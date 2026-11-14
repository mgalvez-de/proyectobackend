<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title . ' · ' : '' }}Gestión UCSC</title>

        <link rel="icon" type="image/png" href="{{ asset('images/logo-ucsc.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <script>
            // estado de la sidebar: abierta en el celular / contraída en el escritorio (se recuerda)
            function layoutState() {
                return {
                    sidebarOpen: false,
                    collapsed: false,
                    init() {
                        try { this.collapsed = localStorage.getItem('sidebar-collapsed') === '1'; } catch (e) {}
                        this.$watch('collapsed', value => {
                            try { localStorage.setItem('sidebar-collapsed', value ? '1' : '0'); } catch (e) {}
                        });
                    },
                };
            }
        </script>

        <div x-data="layoutState()" @keydown.escape.window="sidebarOpen = false" class="min-h-screen flex flex-col">

            <x-navbar />

            <div class="flex flex-1">

                @auth
                    <x-sidebar />
                @endauth

                <div class="flex min-w-0 flex-1 flex-col">
                    <!-- Page Content -->
                    <main class="flex-1">
                        {{ $slot }}
                    </main>

                    <x-footer />
                </div>

            </div>
        </div>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: @json(session('success')),
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        customClass: { container: 'swal-below-navbar' }
                    });
                });
            </script>
        @endif

        @stack('scripts')
    </body>
</html>
