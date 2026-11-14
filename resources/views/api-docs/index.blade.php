<x-app-layout title="Documentación API">


    @php
        $methodColors = ['GET' => 'green', 'POST' => 'blue', 'PUT' => 'yellow', 'DELETE' => 'red'];
        $statusColor = fn ($code) => $code < 300 ? 'green' : ($code === 404 ? 'orange' : ($code === 409 ? 'yellow' : 'red'));
        $pretty = fn ($data) => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // datos para el probador en vivo
        $testerEndpoints = collect($endpoints)->map(fn ($e) => [
            'key' => $e['key'],
            'method' => $e['method'],
            'path' => $e['path'],
            'name' => $e['name'],
            'body' => $e['body'] ? $pretty($e['body']) : null,
        ])->values();
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <x-page-header title="Documentación de la API"
            subtitle="Cómo conectarse a la API de RAM y qué responde cada endpoint."
            :count="count($endpoints)" unit="endpoint"
            :breadcrumbs="['Inicio' => route('dashboard'), 'Documentación API' => null]">
            <x-button :href="route('api-docs.postman')">
                <x-icon name="database" class="w-4 h-4" />
                Descargar colección de Postman
            </x-button>
        </x-page-header>


        <!-- ===================== DATOS DE CONEXIÓN ===================== -->

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Datos para conectarse</h2>

                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="font-semibold text-slate-700 mb-1">URL base</dt>
                        <dd class="flex items-center gap-2">
                            <code class="flex-1 rounded-lg bg-slate-900 px-3 py-2 font-mono text-slate-100">{{ $baseUrl }}</code>
                            <button type="button" onclick="copyText(@js($baseUrl), this)"
                                class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">Copiar</button>
                        </dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-slate-700 mb-1">Headers (en Postman: pestaña <em>Headers</em>)</dt>
                        <dd class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="text-xs uppercase text-slate-500">
                                    <tr><th class="py-1 pr-4">Key</th><th class="py-1 pr-4">Value</th><th class="py-1">Cuándo</th></tr>
                                </thead>
                                <tbody class="font-mono text-slate-700">
                                    <tr><td class="py-1 pr-4">Accept</td><td class="py-1 pr-4">application/json</td><td class="py-1 font-sans text-slate-500">Siempre</td></tr>
                                    <tr><td class="py-1 pr-4">Content-Type</td><td class="py-1 pr-4">application/json</td><td class="py-1 font-sans text-slate-500">Al enviar datos (POST y PUT)</td></tr>
                                </tbody>
                            </table>
                        </dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-slate-700 mb-1">Formato</dt>
                        <dd class="text-slate-600">Todo se envía y se recibe en <strong>JSON</strong>, incluidos los errores.</dd>
                    </div>
                </dl>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-slate-800 mb-3">Antes de probar</h2>
                    <ol class="list-decimal pl-5 space-y-2 text-sm text-slate-600">
                        <li>MySQL encendido en XAMPP.</li>
                        <li>Servidor encendido: <code class="rounded bg-slate-100 px-1 font-mono text-xs">php artisan serve</code></li>
                        <li>En Postman usa la app de escritorio o la extensión de VS Code (la versión web no llega a <code class="font-mono text-xs">127.0.0.1</code>).</li>
                    </ol>
                </div>

                <div class="flex items-start gap-3 rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800">
                    <x-icon name="exclamation" class="w-5 h-5 shrink-0" />
                    <p>Por ahora la API <strong>no pide autenticación</strong>: cualquiera que llegue a la URL puede crear, editar o borrar RAM. Más adelante se puede proteger con tokens de Sanctum.</p>
                </div>
            </div>

        </section>


        <!-- ===================== CÓMO FUNCIONA ===================== -->

        <section class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Cómo funciona</h2>
            <p class="text-sm text-slate-500 mb-5">Recorrido de una petición, desde Postman hasta la base de datos y de vuelta.</p>

            @php
                $flow = [
                    ['icon' => 'squares-plus', 'title' => 'Postman', 'text' => 'Envía la petición: método + URL (+ body).'],
                    ['icon' => 'code', 'title' => 'routes/api.php', 'text' => 'apiResource busca qué método del controlador la atiende.'],
                    ['icon' => 'chip', 'title' => 'Api\\RamController', 'text' => 'Valida los datos y decide qué hacer.'],
                    ['icon' => 'server', 'title' => 'Modelo Ram', 'text' => 'Lee o guarda en la tabla rams de MySQL.'],
                    ['icon' => 'check-badge', 'title' => 'Respuesta JSON', 'text' => 'Vuelve a Postman con un código de estado.'],
                ];
            @endphp

            <ol class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                @foreach ($flow as $i => $step)
                    <li class="relative rounded-lg bg-slate-50 border border-slate-200 p-4">
                        <span class="absolute top-3 right-3 text-xs font-bold text-ucsc">{{ $i + 1 }}</span>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-ucsc text-white mb-3">
                            <x-icon :name="$step['icon']" class="w-5 h-5" />
                        </span>
                        <p class="font-semibold text-slate-800 text-sm font-mono">{{ $step['title'] }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                <div class="rounded-lg border border-slate-200 p-3">
                    <p class="font-mono text-xs text-slate-500">routes/api.php</p>
                    <p class="text-slate-700">Rutas de la API, con el prefijo <code class="font-mono">/api</code> y nombres <code class="font-mono">api.rams.*</code>.</p>
                </div>
                <div class="rounded-lg border border-slate-200 p-3">
                    <p class="font-mono text-xs text-slate-500">app/Http/Controllers/Api/RamController.php</p>
                    <p class="text-slate-700">Lógica de la API. Es distinto del controlador web, que devuelve vistas.</p>
                </div>
                <div class="rounded-lg border border-slate-200 p-3">
                    <p class="font-mono text-xs text-slate-500">bootstrap/app.php</p>
                    <p class="text-slate-700">Registra la API y hace que los errores también salgan en JSON.</p>
                </div>
            </div>
        </section>


        <!-- ===================== RESUMEN DE ENDPOINTS ===================== -->

        <section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="px-6 pt-6 pb-3">
                <h2 class="text-lg font-bold text-slate-800">Endpoints</h2>
                <p class="text-sm text-slate-500">Haz clic en uno para ver el detalle.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="bg-slate-50 border-y border-slate-200 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Método</th>
                            <th class="px-6 py-3">Ruta</th>
                            <th class="px-6 py-3">Qué hace</th>
                            <th class="px-6 py-3">Controlador</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($endpoints as $e)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3"><x-badge :color="$methodColors[$e['method']]">{{ $e['method'] }}</x-badge></td>
                                <td class="px-6 py-3 font-mono text-slate-800"><a href="#{{ $e['key'] }}" class="hover:text-ucsc">{{ $e['path'] }}</a></td>
                                <td class="px-6 py-3 text-slate-600">{{ $e['name'] }}</td>
                                <td class="px-6 py-3 font-mono text-xs text-slate-500">{{ $e['controller'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>


        <!-- ===================== DETALLE DE CADA ENDPOINT ===================== -->

        <div class="space-y-6 mb-8">
            @foreach ($endpoints as $e)
                <section id="{{ $e['key'] }}" class="scroll-mt-6 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

                    <header class="flex flex-wrap items-center gap-3 border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <x-badge :color="$methodColors[$e['method']]">{{ $e['method'] }}</x-badge>
                        <code class="font-mono text-base font-semibold text-slate-800">{{ $e['path'] }}</code>
                        <span class="text-sm text-slate-500">— {{ $e['name'] }}</span>
                        <button type="button" onclick="copyText(@js($baseUrl . str_replace('/api', '', str_replace('{id}', '1', $e['path']))), this)"
                            class="ml-auto rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">Copiar URL</button>
                    </header>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">

                        <div class="space-y-4 text-sm">
                            <p class="text-slate-700">{{ $e['description'] }}</p>

                            @if (str_contains($e['path'], '{id}'))
                                <p class="text-slate-600">
                                    Reemplaza <code class="rounded bg-slate-100 px-1 font-mono">{id}</code> por el ID de la RAM.
                                    Ej: <code class="rounded bg-slate-100 px-1 font-mono">{{ str_replace('{id}', '1', $e['path']) }}</code>
                                </p>
                            @endif

                            @if ($e['body'])
                                <div>
                                    <h3 class="font-semibold text-slate-800 mb-2">Datos que hay que enviar (Body → raw → JSON)</h3>
                                    <table class="w-full text-left text-sm border border-slate-200 rounded-lg overflow-hidden">
                                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                                            <tr><th class="px-3 py-2">Campo</th><th class="px-3 py-2">Tipo</th><th class="px-3 py-2">Obligatorio</th><th class="px-3 py-2">Ejemplo</th></tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <tr><td class="px-3 py-2 font-mono">type</td><td class="px-3 py-2">texto (máx. 50)</td><td class="px-3 py-2">Sí</td><td class="px-3 py-2 font-mono">"{{ $e['body']['type'] }}"</td></tr>
                                            <tr><td class="px-3 py-2 font-mono">capacity</td><td class="px-3 py-2">texto (máx. 50)</td><td class="px-3 py-2">Sí</td><td class="px-3 py-2 font-mono">"{{ $e['body']['capacity'] }}"</td></tr>
                                        </tbody>
                                    </table>
                                    <pre class="mt-3 overflow-x-auto rounded-lg bg-slate-900 p-4 font-mono text-xs text-slate-100">{{ $pretty($e['body']) }}</pre>
                                </div>
                            @endif

                            <div class="rounded-lg bg-slate-50 border border-slate-200 p-4">
                                <h3 class="font-semibold text-slate-800 mb-2">En Postman</h3>
                                <ol class="list-decimal pl-5 space-y-1 text-slate-600">
                                    <li>Método <strong>{{ $e['method'] }}</strong> y la URL de arriba.</li>
                                    <li>Header <code class="font-mono text-xs">Accept: application/json</code>.</li>
                                    @if ($e['body'])
                                        <li>Body → <strong>raw</strong> → <strong>JSON</strong>, y pega el ejemplo.</li>
                                    @endif
                                    <li>Presiona <strong>Send</strong>.</li>
                                </ol>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-800 mb-2 text-sm">Respuestas</h3>
                            <div class="space-y-3">
                                @foreach ($e['responses'] as [$code, $label, $example])
                                    <div class="rounded-lg border border-slate-200 overflow-hidden">
                                        <div class="flex items-center gap-2 bg-slate-50 px-3 py-2 text-sm">
                                            <x-badge :color="$statusColor($code)">{{ $code }}</x-badge>
                                            <span class="text-slate-600">{{ $label }}</span>
                                        </div>
                                        <pre class="max-h-64 overflow-auto bg-slate-900 p-4 font-mono text-xs text-slate-100">{{ $pretty($example) }}</pre>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </section>
            @endforeach
        </div>


        <!-- ===================== CÓDIGOS DE ESTADO ===================== -->

        <section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="px-6 pt-6 pb-3">
                <h2 class="text-lg font-bold text-slate-800">Códigos de estado</h2>
                <p class="text-sm text-slate-500">El número que aparece en Postman junto a la respuesta.</p>
            </div>
            <table class="w-full text-left text-sm">
                <tbody class="divide-y divide-slate-100 border-t border-slate-200">
                    @foreach ([
                        [200, 'OK', 'Todo salió bien.'],
                        [201, 'Created', 'Se creó el registro (POST).'],
                        [404, 'Not Found', 'El ID no existe o la URL está mal escrita.'],
                        [405, 'Method Not Allowed', 'El método no corresponde a esa URL (ej: POST a /api/rams/1).'],
                        [409, 'Conflict', 'No se puede eliminar porque hay dispositivos usando esa RAM.'],
                        [422, 'Unprocessable Content', 'Faltan datos o son inválidos. El JSON trae el detalle en "errors".'],
                        [500, 'Server Error', 'Error del servidor. Lo más común: MySQL apagado.'],
                    ] as [$code, $name, $text])
                        <tr>
                            <td class="px-6 py-3 w-24"><x-badge :color="$statusColor($code)">{{ $code }}</x-badge></td>
                            <td class="px-6 py-3 font-mono text-slate-700 w-56">{{ $name }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $text }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>


        <!-- ===================== PROBADOR EN VIVO ===================== -->

        <section x-data="apiTester(@js($testerEndpoints))" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Probar la API desde aquí</h2>
            <p class="text-sm text-slate-500 mb-5">Hace la misma petición que harías en Postman. <strong class="text-ucsc">Ojo:</strong> crear, editar y eliminar cambian los datos reales.</p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Endpoint</label>
                        <select x-model="selected" @change="loadBody()"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc">
                            <template x-for="e in endpoints" :key="e.key">
                                <option :value="e.key" x-text="e.method + '  ' + e.path + ' — ' + e.name"></option>
                            </template>
                        </select>
                    </div>

                    <div x-show="current().path.includes('{id}')">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ID de la RAM</label>
                        <input type="number" min="1" x-model="id"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc">
                    </div>

                    <div x-show="current().body !== null">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Body (JSON)</label>
                        <textarea x-model="body" rows="5"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc"></textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <code class="flex-1 truncate rounded-lg bg-slate-100 px-3 py-2 font-mono text-xs text-slate-700" x-text="current().method + ' ' + url()"></code>
                        <x-button type="button" x-on:click="send()" x-bind:disabled="loading">
                            <span x-text="loading ? 'Enviando...' : 'Send'">Send</span>
                        </x-button>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-3 mb-2 text-sm">
                        <span class="font-semibold text-slate-700">Respuesta</span>
                        <template x-if="status">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                :class="status < 300 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'" x-text="status"></span>
                        </template>
                        <span x-show="time" class="text-xs text-slate-400" x-text="time + ' ms'"></span>
                    </div>
                    <pre class="h-80 overflow-auto rounded-lg bg-slate-900 p-4 font-mono text-xs text-slate-100" x-text="output"></pre>
                </div>

            </div>
        </section>

    </div>

    <script>
        function copyText(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                const original = button.textContent;
                button.textContent = '¡Copiado!';
                setTimeout(() => button.textContent = original, 1500);
            });
        }

        // probador en vivo: usa la URL relativa /api para funcionar en cualquier host
        function apiTester(endpoints) {
            return {
                endpoints,
                selected: endpoints[0].key,
                id: 1,
                body: '',
                status: null,
                time: null,
                output: 'Elige un endpoint y presiona Send.',
                loading: false,

                current() {
                    return this.endpoints.find(e => e.key === this.selected);
                },
                url() {
                    return '/api' + this.current().path.replace('/api', '').replace('{id}', this.id || '1');
                },
                loadBody() {
                    this.body = this.current().body ?? '';
                },
                async send() {
                    const endpoint = this.current();

                    if (endpoint.method === 'DELETE' && !confirm('¿Eliminar la RAM con ID ' + this.id + '? Esto borra el dato real.')) {
                        return;
                    }

                    const options = {
                        method: endpoint.method,
                        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    };

                    if (endpoint.body !== null) {
                        try {
                            JSON.parse(this.body);
                        } catch (error) {
                            this.status = null;
                            this.output = 'El body no es un JSON válido: ' + error.message;
                            return;
                        }
                        options.body = this.body;
                    }

                    this.loading = true;
                    const start = performance.now();

                    try {
                        const response = await fetch(this.url(), options);
                        const text = await response.text();
                        this.status = response.status;
                        this.time = Math.round(performance.now() - start);

                        try {
                            this.output = JSON.stringify(JSON.parse(text), null, 2);
                        } catch {
                            this.output = text || '(respuesta vacía)';
                        }
                    } catch (error) {
                        this.status = null;
                        this.output = 'No se pudo conectar con la API: ' + error.message;
                    } finally {
                        this.loading = false;
                    }
                },
                init() {
                    this.loadBody();
                },
            };
        }
    </script>

</x-app-layout>
