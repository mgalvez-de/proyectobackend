<x-app-layout>

    <x-navbar />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @if (auth()->user()->rol === 'admin')
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
                Volver al dashboard
            </a>
        @endif

        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mt-3 mb-6">

            <h1 class="text-3xl font-bold text-gray-800">
                Historial del departamento: {{ $department->name }}
            </h1>

        </div>


        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table
                class="w-full min-w-[800px] text-left text-sm text-gray-600 bg-white rounded-lg shadow-sm border border-gray-200">

                <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-4">ID</th>
                        <th class="px-5 py-4">Dispositivo</th>
                        <th class="px-5 py-4">Tipo</th>
                        <th class="px-5 py-4">Falla</th>
                        <th class="px-5 py-4">Gravedad</th>
                        <th class="px-5 py-4">Estado</th>
                        <th class="px-5 py-4">Fecha</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td class="px-5 py-4">
                                #{{ $ticket->id }}
                            </td>

                            <td class="px-5 py-4 font-medium text-gray-800">
                                {{ $ticket->device?->name ?? 'Sin dispositivo' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ ucfirst($ticket->problem_type) }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $ticket->error_description }}
                            </td>

                            <td class="px-5 py-4">
                                {{ [
                                    'low' => 'Baja',
                                    'medium' => 'Media',
                                    'high' => 'Alta',
                                    'critical' => 'Crítica',
                                ][$ticket->severity] ?? 'Sin definir' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ [
                                    'pending' => 'Pendiente',
                                    'in_progress' => 'En proceso',
                                    'closed' => 'Cerrado',
                                ][$ticket->status] ?? $ticket->status }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-500">
                                Este departamento todavía no tiene tickets registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</x-app-layout>
