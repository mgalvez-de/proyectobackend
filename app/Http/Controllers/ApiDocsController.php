<?php

namespace App\Http\Controllers;

// Vista con la documentación de la API y descarga de la colección de Postman
class ApiDocsController extends Controller
{
    public function index()
    {
        $baseUrl = url('/api');
        $endpoints = $this->endpoints();

        return view('api-docs.index', compact('baseUrl', 'endpoints'));
    }

    // Colección para importar en Postman (Import -> archivo .json)
    public function postman()
    {
        $items = collect($this->endpoints())->map(function ($e) {
            $path = str_replace('{id}', '1', ltrim(str_replace('/api', '', $e['path']), '/'));

            $request = [
                'method' => $e['method'],
                'header' => [
                    ['key' => 'Accept', 'value' => 'application/json'],
                    ['key' => 'Content-Type', 'value' => 'application/json'],
                ],
                'url' => [
                    'raw' => '{{base_url}}/' . $path,
                    'host' => ['{{base_url}}'],
                    'path' => explode('/', $path),
                ],
                'description' => $e['description'],
            ];

            if ($e['body']) {
                $request['body'] = [
                    'mode' => 'raw',
                    'raw' => json_encode($e['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                    'options' => ['raw' => ['language' => 'json']],
                ];
            }

            return ['name' => $e['name'], 'request' => $request];
        })->values()->all();

        $collection = [
            'info' => [
                'name' => 'API Gestión UCSC - RAM',
                'description' => 'Endpoints de la API de RAM del Sistema de Gestión de Dispositivos Tecnológicos UCSC.',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'variable' => [
                ['key' => 'base_url', 'value' => url('/api')],
            ],
            'item' => $items,
        ];

        return response()->json($collection, 200, [
            'Content-Disposition' => 'attachment; filename="API-Gestion-UCSC-RAM.postman_collection.json"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function endpoints(): array
    {
        $ram = ['id' => 1, 'type' => 'DDR4', 'capacity' => '8 GB', 'created_at' => '2026-10-19T12:00:00.000000Z', 'updated_at' => '2026-10-19T12:00:00.000000Z'];
        $notFound = ['message' => 'No se encontró el recurso solicitado.'];
        $invalid = [
            'message' => 'El tipo de RAM es obligatorio. Ej: DDR4 (and 1 more error)',
            'errors' => [
                'type' => ['El tipo de RAM es obligatorio. Ej: DDR4'],
                'capacity' => ['La capacidad es obligatoria. Ej: 16 GB'],
            ],
        ];

        return [
            [
                'key' => 'index',
                'method' => 'GET',
                'path' => '/api/rams',
                'name' => 'Listar RAM',
                'description' => 'Devuelve las RAM registradas, de 15 en 15. Para ver otra página agrega ?page=2 al final de la URL.',
                'controller' => 'index()',
                'body' => null,
                'responses' => [
                    [200, 'Lista paginada de RAM', [
                        'current_page' => 1,
                        'data' => [$ram],
                        'per_page' => 15,
                        'total' => 1,
                        'last_page' => 1,
                    ]],
                ],
            ],
            [
                'key' => 'show',
                'method' => 'GET',
                'path' => '/api/rams/{id}',
                'name' => 'Ver una RAM',
                'description' => 'Devuelve la RAM con el ID indicado.',
                'controller' => 'show()',
                'body' => null,
                'responses' => [
                    [200, 'RAM encontrada', ['data' => $ram]],
                    [404, 'El ID no existe', $notFound],
                ],
            ],
            [
                'key' => 'store',
                'method' => 'POST',
                'path' => '/api/rams',
                'name' => 'Crear una RAM',
                'description' => 'Registra una RAM nueva con los datos enviados en el body.',
                'controller' => 'store()',
                'body' => ['type' => 'DDR5', 'capacity' => '32 GB'],
                'responses' => [
                    [201, 'RAM creada', ['message' => 'RAM creada correctamente.', 'data' => array_merge($ram, ['id' => 4, 'type' => 'DDR5', 'capacity' => '32 GB'])]],
                    [422, 'Faltan datos o son inválidos', $invalid],
                ],
            ],
            [
                'key' => 'update',
                'method' => 'PUT',
                'path' => '/api/rams/{id}',
                'name' => 'Editar una RAM',
                'description' => 'Reemplaza el tipo y la capacidad de la RAM con el ID indicado.',
                'controller' => 'update()',
                'body' => ['type' => 'DDR4', 'capacity' => '16 GB'],
                'responses' => [
                    [200, 'RAM actualizada', ['message' => 'RAM actualizada correctamente.', 'data' => array_merge($ram, ['capacity' => '16 GB'])]],
                    [404, 'El ID no existe', $notFound],
                    [422, 'Faltan datos o son inválidos', $invalid],
                ],
            ],
            [
                'key' => 'destroy',
                'method' => 'DELETE',
                'path' => '/api/rams/{id}',
                'name' => 'Eliminar una RAM',
                'description' => 'Elimina la RAM con el ID indicado. No se puede eliminar si algún dispositivo la está usando.',
                'controller' => 'destroy()',
                'body' => null,
                'responses' => [
                    [200, 'RAM eliminada', ['message' => 'RAM eliminada correctamente.']],
                    [404, 'El ID no existe', $notFound],
                    [409, 'Hay dispositivos usando esta RAM', ['message' => 'No se puede eliminar: 2 dispositivo(s) usan esta RAM.']],
                ],
            ],
        ];
    }
}
