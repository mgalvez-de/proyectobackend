<?php

namespace App\Http\Controllers;

// Vista con la documentación de la API (2 públicas + 2 privadas) y descarga de la colección de Postman
class ApiDocsController extends Controller
{
    public function index()
    {
        $baseUrl = url('/api');
        $endpoints = $this->endpoints();
        $groups = collect($endpoints)->groupBy('group');

        return view('api-docs.index', compact('baseUrl', 'endpoints', 'groups'));
    }

    // Colección para importar en Postman (Import -> archivo .json)
    public function postman()
    {
        $folders = collect($this->endpoints())->groupBy('group')->map(function ($endpoints, $group) {
            return [
                'name' => $group,
                'item' => $endpoints->map(function ($e) {
                    $path = str_replace('{id}', '1', ltrim(str_replace('/api', '', $e['path']), '/'));

                    $headers = [
                        ['key' => 'Accept', 'value' => 'application/json'],
                        ['key' => 'Content-Type', 'value' => 'application/json'],
                    ];

                    // las rutas privadas llevan el token que entrega POST /api/login
                    if ($e['auth']) {
                        $headers[] = ['key' => 'Authorization', 'value' => 'Bearer {{token}}'];
                    }

                    $request = [
                        'method' => $e['method'],
                        'header' => $headers,
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
                })->values()->all(),
            ];
        })->values()->all();

        $collection = [
            'info' => [
                'name' => 'API Gestión UCSC',
                'description' => 'APIs públicas (RAM y procesadores) y privadas con token (equipos y estadísticas) del Sistema de Gestión de Dispositivos Tecnológicos UCSC.',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'variable' => [
                ['key' => 'base_url', 'value' => url('/api')],
                ['key' => 'token', 'value' => 'pega-aqui-el-token-de-login'],
            ],
            'item' => $folders,
        ];

        return response()->json($collection, 200, [
            'Content-Disposition' => 'attachment; filename="API-Gestion-UCSC.postman_collection.json"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    // Cada endpoint: grupo, acceso (null = pública, 'token' o 'admin'), campos del body y respuestas de ejemplo
    private function endpoints(): array
    {
        $date = '2026-11-15T12:00:00.000000Z';
        $notFound = ['message' => 'No se encontró el recurso solicitado.'];
        $noToken = ['message' => 'No autenticado. Debes enviar un token válido en el header Authorization: Bearer {token}.'];
        $notAdmin = ['message' => 'No tienes permiso para realizar esta acción.'];

        $ram = ['id' => 1, 'type' => 'DDR4', 'capacity' => '8 GB', 'created_at' => $date, 'updated_at' => $date];
        $ramInvalid = [
            'message' => 'El tipo de RAM es obligatorio. Ej: DDR4 (and 1 more error)',
            'errors' => [
                'type' => ['El tipo de RAM es obligatorio. Ej: DDR4'],
                'capacity' => ['La capacidad es obligatoria. Ej: 16 GB'],
            ],
        ];
        $ramFields = [
            ['type', 'texto (máx. 50)', 'Sí'],
            ['capacity', 'texto (máx. 50)', 'Sí'],
        ];

        $processor = ['id' => 1, 'brand' => 'Intel', 'model' => 'Core i5-12400', 'created_at' => $date, 'updated_at' => $date];
        $processorInvalid = [
            'message' => 'The brand field is required. (and 1 more error)',
            'errors' => ['brand' => ['The brand field is required.'], 'model' => ['The model field is required.']],
        ];
        $processorFields = [
            ['brand', 'texto (máx. 255)', 'Sí'],
            ['model', 'texto (máx. 255)', 'Sí'],
        ];

        $device = [
            'id' => 1, 'name' => 'PC-INF-01', 'brand_id' => 2, 'storage_id' => 3, 'department_id' => 1,
            'ram_id' => 2, 'processor_id' => 1, 'device_type_id' => 1,
            'brand' => ['id' => 2, 'name' => 'Lenovo'],
            'ram' => ['id' => 2, 'type' => 'DDR4', 'capacity' => '16 GB'],
            'processor' => ['id' => 1, 'brand' => 'Intel', 'model' => 'Core i5-12400'],
            'device_type' => ['id' => 1, 'name' => 'Computador de escritorio'],
            'department' => ['id' => 1, 'name' => 'Informática'],
        ];
        $deviceBody = ['name' => 'PC-INF-04', 'brand_id' => 1, 'storage_id' => 1, 'ram_id' => 1, 'processor_id' => 1, 'device_type_id' => 1, 'department_id' => 1];
        $deviceFields = [
            ['name', 'texto (máx. 255)', 'Sí'],
            ['brand_id', 'número (ID de una marca)', 'Sí'],
            ['storage_id', 'número (ID de un almacenamiento)', 'Sí'],
            ['ram_id', 'número (ID de una RAM)', 'Sí'],
            ['processor_id', 'número (ID de un procesador)', 'Sí'],
            ['device_type_id', 'número (ID de un tipo)', 'Sí'],
            ['department_id', 'número (ID del departamento)', 'Sí'],
        ];

        $ramGroup = 'RAM · API pública';
        $processorGroup = 'Procesadores · API pública';
        $authGroup = 'Autenticación (token)';
        $deviceGroup = 'Equipos · API privada';
        $statsGroup = 'Estadísticas · API privada (admin)';

        return [
            // ---------------- RAM (pública) ----------------
            ['key' => 'ram-index', 'group' => $ramGroup, 'auth' => null, 'resource' => 'la RAM',
                'method' => 'GET', 'path' => '/api/rams', 'name' => 'Listar RAM', 'controller' => 'Api\RamController@index',
                'description' => 'Devuelve las RAM registradas, de 15 en 15. Para ver otra página agrega ?page=2 al final de la URL.',
                'body' => null, 'fields' => [],
                'responses' => [[200, 'Lista paginada de RAM', ['current_page' => 1, 'data' => [$ram], 'per_page' => 15, 'total' => 1, 'last_page' => 1]]]],
            ['key' => 'ram-show', 'group' => $ramGroup, 'auth' => null, 'resource' => 'la RAM',
                'method' => 'GET', 'path' => '/api/rams/{id}', 'name' => 'Ver una RAM', 'controller' => 'Api\RamController@show',
                'description' => 'Devuelve la RAM con el ID indicado.',
                'body' => null, 'fields' => [],
                'responses' => [[200, 'RAM encontrada', ['data' => $ram]], [404, 'El ID no existe', $notFound]]],
            ['key' => 'ram-store', 'group' => $ramGroup, 'auth' => null, 'resource' => 'la RAM',
                'method' => 'POST', 'path' => '/api/rams', 'name' => 'Crear una RAM', 'controller' => 'Api\RamController@store',
                'description' => 'Registra una RAM nueva con los datos enviados en el body.',
                'body' => ['type' => 'DDR5', 'capacity' => '32 GB'], 'fields' => $ramFields,
                'responses' => [[201, 'RAM creada', ['message' => 'RAM creada correctamente.', 'data' => array_merge($ram, ['id' => 4, 'type' => 'DDR5', 'capacity' => '32 GB'])]], [422, 'Faltan datos o son inválidos', $ramInvalid]]],
            ['key' => 'ram-update', 'group' => $ramGroup, 'auth' => null, 'resource' => 'la RAM',
                'method' => 'PUT', 'path' => '/api/rams/{id}', 'name' => 'Editar una RAM', 'controller' => 'Api\RamController@update',
                'description' => 'Reemplaza el tipo y la capacidad de la RAM con el ID indicado.',
                'body' => ['type' => 'DDR4', 'capacity' => '16 GB'], 'fields' => $ramFields,
                'responses' => [[200, 'RAM actualizada', ['message' => 'RAM actualizada correctamente.', 'data' => array_merge($ram, ['capacity' => '16 GB'])]], [404, 'El ID no existe', $notFound], [422, 'Faltan datos o son inválidos', $ramInvalid]]],
            ['key' => 'ram-destroy', 'group' => $ramGroup, 'auth' => null, 'resource' => 'la RAM',
                'method' => 'DELETE', 'path' => '/api/rams/{id}', 'name' => 'Eliminar una RAM', 'controller' => 'Api\RamController@destroy',
                'description' => 'Elimina la RAM con el ID indicado. No se puede eliminar si algún dispositivo la está usando.',
                'body' => null, 'fields' => [],
                'responses' => [[200, 'RAM eliminada', ['message' => 'RAM eliminada correctamente.']], [404, 'El ID no existe', $notFound], [409, 'Hay dispositivos usando esta RAM', ['message' => 'No se puede eliminar: 2 dispositivo(s) usan esta RAM.']]]],

            // ---------------- Procesadores (pública) ----------------
            ['key' => 'processor-index', 'group' => $processorGroup, 'auth' => null, 'resource' => 'el procesador',
                'method' => 'GET', 'path' => '/api/processors', 'name' => 'Listar procesadores', 'controller' => 'Api\ProcessorController@index',
                'description' => 'Devuelve los procesadores registrados, de 15 en 15 (?page=2 para la siguiente página).',
                'body' => null, 'fields' => [],
                'responses' => [[200, 'Lista paginada de procesadores', ['current_page' => 1, 'data' => [$processor], 'per_page' => 15, 'total' => 1, 'last_page' => 1]]]],
            ['key' => 'processor-show', 'group' => $processorGroup, 'auth' => null, 'resource' => 'el procesador',
                'method' => 'GET', 'path' => '/api/processors/{id}', 'name' => 'Ver un procesador', 'controller' => 'Api\ProcessorController@show',
                'description' => 'Devuelve el procesador con el ID indicado.',
                'body' => null, 'fields' => [],
                'responses' => [[200, 'Procesador encontrado', $processor], [404, 'El ID no existe', $notFound]]],
            ['key' => 'processor-store', 'group' => $processorGroup, 'auth' => null, 'resource' => 'el procesador',
                'method' => 'POST', 'path' => '/api/processors', 'name' => 'Crear un procesador', 'controller' => 'Api\ProcessorController@store',
                'description' => 'Registra un procesador nuevo con la marca y el modelo.',
                'body' => ['brand' => 'AMD', 'model' => 'Ryzen 7 5700G'], 'fields' => $processorFields,
                'responses' => [[201, 'Procesador creado', array_merge($processor, ['id' => 5, 'brand' => 'AMD', 'model' => 'Ryzen 7 5700G'])], [422, 'Faltan datos o son inválidos', $processorInvalid]]],
            ['key' => 'processor-update', 'group' => $processorGroup, 'auth' => null, 'resource' => 'el procesador',
                'method' => 'PUT', 'path' => '/api/processors/{id}', 'name' => 'Editar un procesador', 'controller' => 'Api\ProcessorController@update',
                'description' => 'Reemplaza la marca y el modelo del procesador con el ID indicado.',
                'body' => ['brand' => 'Intel', 'model' => 'Core i7-12700'], 'fields' => $processorFields,
                'responses' => [[200, 'Procesador actualizado', array_merge($processor, ['model' => 'Core i7-12700'])], [404, 'El ID no existe', $notFound], [422, 'Faltan datos o son inválidos', $processorInvalid]]],
            ['key' => 'processor-destroy', 'group' => $processorGroup, 'auth' => null, 'resource' => 'el procesador',
                'method' => 'DELETE', 'path' => '/api/processors/{id}', 'name' => 'Eliminar un procesador', 'controller' => 'Api\ProcessorController@destroy',
                'description' => 'Elimina el procesador. Los equipos que lo usaban quedan sin procesador asignado.',
                'body' => null, 'fields' => [],
                'responses' => [[204, 'Procesador eliminado (respuesta sin contenido)', null], [404, 'El ID no existe', $notFound]]],

            // ---------------- Autenticación ----------------
            ['key' => 'auth-login', 'group' => $authGroup, 'auth' => null, 'resource' => null,
                'method' => 'POST', 'path' => '/api/login', 'name' => 'Iniciar sesión y obtener el token', 'controller' => 'Api\AuthController@login',
                'description' => 'Recibe el correo y la contraseña de un usuario del sistema y entrega el token para usar la API privada.',
                'body' => ['email' => 'admin@ucsc.cl', 'password' => 'tu-contraseña', 'device_name' => 'postman'],
                'fields' => [['email', 'correo', 'Sí'], ['password', 'texto', 'Sí'], ['device_name', 'texto (nombre del token)', 'No']],
                'responses' => [
                    [200, 'Sesión iniciada', ['message' => 'Sesión iniciada correctamente.', 'token_type' => 'Bearer', 'token' => '3|Xk9...(token)', 'user' => ['id' => 1, 'name' => 'Administrador', 'email' => 'admin@ucsc.cl', 'rol' => 'admin', 'department_id' => null]]],
                    [422, 'Correo o contraseña incorrectos', ['message' => 'Las credenciales no son correctas.', 'errors' => ['email' => ['Las credenciales no son correctas.']]]],
                ]],
            ['key' => 'auth-me', 'group' => $authGroup, 'auth' => 'token', 'resource' => null,
                'method' => 'GET', 'path' => '/api/me', 'name' => 'Ver el usuario del token', 'controller' => 'Api\AuthController@me',
                'description' => 'Devuelve los datos del usuario dueño del token enviado.',
                'body' => null, 'fields' => [],
                'responses' => [[200, 'Usuario del token', ['id' => 1, 'name' => 'Administrador', 'email' => 'admin@ucsc.cl', 'rol' => 'admin', 'department' => null]], [401, 'Falta el token o no es válido', $noToken]]],
            ['key' => 'auth-logout', 'group' => $authGroup, 'auth' => 'token', 'resource' => null,
                'method' => 'POST', 'path' => '/api/logout', 'name' => 'Cerrar sesión (revocar el token)', 'controller' => 'Api\AuthController@logout',
                'description' => 'Elimina el token con el que se hizo la petición. Después de esto ya no sirve.',
                'body' => null, 'fields' => [],
                'responses' => [[200, 'Token revocado', ['message' => 'Sesión cerrada, token revocado.']], [401, 'Falta el token o no es válido', $noToken]]],

            // ---------------- Equipos (privada) ----------------
            ['key' => 'device-index', 'group' => $deviceGroup, 'auth' => 'token', 'resource' => 'el equipo',
                'method' => 'GET', 'path' => '/api/devices', 'name' => 'Listar equipos', 'controller' => 'Api\DeviceController@index',
                'description' => 'Lista los equipos con su marca, RAM, procesador, tipo y departamento. El admin ve todos; el supervisor solo los de su departamento. Filtros opcionales: ?department_id=1 y ?search=PC.',
                'body' => null, 'fields' => [],
                'responses' => [[200, 'Lista paginada de equipos', ['current_page' => 1, 'data' => [$device], 'per_page' => 15, 'total' => 1]], [401, 'Falta el token o no es válido', $noToken]]],
            ['key' => 'device-show', 'group' => $deviceGroup, 'auth' => 'token', 'resource' => 'el equipo',
                'method' => 'GET', 'path' => '/api/devices/{id}', 'name' => 'Ver un equipo', 'controller' => 'Api\DeviceController@show',
                'description' => 'Devuelve el equipo con todas sus relaciones. Un supervisor no puede ver equipos de otro departamento.',
                'body' => null, 'fields' => [],
                'responses' => [[200, 'Equipo encontrado', $device], [401, 'Falta el token o no es válido', $noToken], [403, 'El equipo es de otro departamento', ['message' => 'No tienes acceso a este departamento.']], [404, 'El ID no existe', $notFound]]],
            ['key' => 'device-store', 'group' => $deviceGroup, 'auth' => 'token', 'resource' => 'el equipo',
                'method' => 'POST', 'path' => '/api/devices', 'name' => 'Crear un equipo', 'controller' => 'Api\DeviceController@store',
                'description' => 'Registra un equipo nuevo. Todos los *_id deben existir en sus tablas.',
                'body' => $deviceBody, 'fields' => $deviceFields,
                'responses' => [[201, 'Equipo creado', array_merge($device, ['id' => 10, 'name' => 'PC-INF-04'])], [401, 'Falta el token o no es válido', $noToken], [422, 'Faltan datos o algún ID no existe', ['message' => 'The selected brand id is invalid.', 'errors' => ['brand_id' => ['The selected brand id is invalid.']]]]]],
            ['key' => 'device-update', 'group' => $deviceGroup, 'auth' => 'token', 'resource' => 'el equipo',
                'method' => 'PUT', 'path' => '/api/devices/{id}', 'name' => 'Editar un equipo', 'controller' => 'Api\DeviceController@update',
                'description' => 'Cambia solo los campos que se envíen (por ejemplo, solo el nombre o solo la RAM).',
                'body' => ['name' => 'PC-INF-01-B', 'ram_id' => 2], 'fields' => [['name', 'texto (máx. 255)', 'No'], ['ram_id', 'número (ID de una RAM)', 'No']],
                'responses' => [[200, 'Equipo actualizado', array_merge($device, ['name' => 'PC-INF-01-B'])], [401, 'Falta el token o no es válido', $noToken], [403, 'El equipo es de otro departamento', ['message' => 'No tienes acceso a este departamento.']]]],
            ['key' => 'device-destroy', 'group' => $deviceGroup, 'auth' => 'admin', 'resource' => 'el equipo',
                'method' => 'DELETE', 'path' => '/api/devices/{id}', 'name' => 'Eliminar un equipo', 'controller' => 'Api\DeviceController@destroy',
                'description' => 'Elimina el equipo. Solo el administrador puede hacerlo.',
                'body' => null, 'fields' => [],
                'responses' => [[204, 'Equipo eliminado (respuesta sin contenido)', null], [401, 'Falta el token o no es válido', $noToken], [403, 'El token no es de un administrador', $notAdmin]]],

            // ---------------- Estadísticas (privada, admin) ----------------
            ['key' => 'stats', 'group' => $statsGroup, 'auth' => 'admin', 'resource' => null,
                'method' => 'GET', 'path' => '/api/stats', 'name' => 'Resumen del inventario', 'controller' => 'Api\DeviceController@stats',
                'description' => 'Cantidad total de equipos, cuántos hay por departamento y por procesador (las llaves son los IDs).',
                'body' => null, 'fields' => [],
                'responses' => [[200, 'Resumen', ['total_equipos' => 9, 'equipos_por_depto' => ['1' => 3, '2' => 3, '3' => 3], 'equipos_por_procesador' => ['1' => 4, '2' => 2, '3' => 3]]], [401, 'Falta el token o no es válido', $noToken], [403, 'El token no es de un administrador', $notAdmin]]],
        ];
    }
}
