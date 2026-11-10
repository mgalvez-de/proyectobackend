# API REST — Inventario de equipos

## Base URL

Levanta el servidor con:

```bash
php artisan serve
```

y usa esta base URL:

```
http://127.0.0.1:8000/api
```

> **Ojo:** `/api` por sí solo antes daba 404 porque en una API REST cada recurso tiene
> su propia URL. Ahora `GET /api` devuelve la lista de endpoints, pero los datos están
> en `/api/processors`, `/api/rams`, `/api/devices`, etc.
>
> Si en vez de `artisan serve` quieres usar el Apache de XAMPP, primero inícialo desde
> el panel de XAMPP y entonces la base URL es
> `http://localhost/xampp/proyectobackend/public/api`.

En **todas** las peticiones manda estos headers:

```
Accept: application/json
Content-Type: application/json
```

---

## 1) API REST PÚBLICA (sin autenticación)

Definida en `routes/api.php` fuera de cualquier middleware.

### RAMs — `App\Http\Controllers\Api\RamController`

| Método | Endpoint | Qué hace |
|---|---|---|
| GET | `/api/rams` | Lista paginada (15 por página) |
| POST | `/api/rams` | Crea una RAM (`type`, `capacity`) |
| GET | `/api/rams/{id}` | Muestra una RAM |
| PUT/PATCH | `/api/rams/{id}` | Actualiza |
| DELETE | `/api/rams/{id}` | Elimina (204) |

### Procesadores — `App\Http\Controllers\Api\ProcessorController`

| Método | Endpoint | Qué hace |
|---|---|---|
| GET | `/api/processors` | Lista paginada (15 por página) |
| POST | `/api/processors` | Crea un procesador (`brand`, `model`) |
| GET | `/api/processors/{id}` | Muestra un procesador |
| PUT/PATCH | `/api/processors/{id}` | Actualiza |
| DELETE | `/api/processors/{id}` | Elimina (204) |

Ejemplos:

```bash
# Listar
curl http://localhost:8000/api/processors -H "Accept: application/json"

# Crear
curl -X POST http://localhost:8000/api/processors \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"brand":"Intel","model":"Core i5-12400F"}'

# Actualizar
curl -X PUT http://localhost:8000/api/processors/1 \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"brand":"AMD","model":"Ryzen 5 5600X"}'

# Eliminar
curl -X DELETE http://localhost:8000/api/processors/1 -H "Accept: application/json"
```

Respuesta de error de validación (422):

```json
{
  "message": "The brand field is required.",
  "errors": { "brand": ["The brand field is required."] }
}
```

---

## 2) API REST PRIVADA (middleware `auth:sanctum`)

La protección se hace con **middleware**, en `routes/api.php`:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('devices', DeviceController::class)->except(['destroy']);

    Route::middleware('role:admin')->group(function () {   // middleware encadenado
        Route::delete('/devices/{device}', [DeviceController::class, 'destroy']);
        Route::get('/stats', [DeviceController::class, 'stats']);
    });
});
```

- `auth:sanctum` → exige un token personal válido.
- `role:admin` → alias registrado en `bootstrap/app.php` que apunta a
  `App\Http\Middleware\EnsureUserHasRole`.

### Paso 1 — Conseguir el token

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"email":"admin@correo.cl","password":"password","device_name":"postman"}'
```

```json
{
  "message": "Sesión iniciada correctamente.",
  "token_type": "Bearer",
  "token": "3|K9fT...",
  "user": { "id": 1, "name": "Admin", "email": "admin@correo.cl", "rol": "admin", "department_id": null }
}
```

### Paso 2 — Usar el token en cada petición

```
Authorization: Bearer 3|K9fT...
```

| Método | Endpoint | Middleware | Qué hace |
|---|---|---|---|
| POST | `/api/login` | — (público) | Entrega el token |
| GET | `/api/me` | `auth:sanctum` | Usuario dueño del token |
| GET | `/api/user` | `auth:sanctum` | Usuario crudo |
| POST | `/api/logout` | `auth:sanctum` | Revoca el token actual |
| GET | `/api/devices` | `auth:sanctum` | Lista equipos (filtros: `?department_id=`, `?search=`) |
| POST | `/api/devices` | `auth:sanctum` | Crea equipo |
| GET | `/api/devices/{id}` | `auth:sanctum` | Muestra equipo con sus relaciones |
| PUT/PATCH | `/api/devices/{id}` | `auth:sanctum` | Actualiza equipo |
| DELETE | `/api/devices/{id}` | `auth:sanctum` + `role:admin` | Elimina equipo |
| GET | `/api/stats` | `auth:sanctum` + `role:admin` | Resumen del inventario |

Ejemplos:

```bash
TOKEN="3|K9fT..."

# Listar mis equipos
curl http://localhost:8000/api/devices \
  -H "Accept: application/json" -H "Authorization: Bearer $TOKEN"

# Crear un equipo
curl -X POST http://localhost:8000/api/devices \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"name":"PC-LAB-01","brand_id":1,"storage_id":1,"ram_id":1,"processor_id":1,"device_type_id":1,"department_id":1}'

# Eliminar (sólo admin)
curl -X DELETE http://localhost:8000/api/devices/5 \
  -H "Accept: application/json" -H "Authorization: Bearer $TOKEN"

# Cerrar sesión
curl -X POST http://localhost:8000/api/logout \
  -H "Accept: application/json" -H "Authorization: Bearer $TOKEN"
```

### Respuestas del middleware

Sin token (401):

```json
{ "message": "No autenticado. Debes enviar un token válido en el header Authorization: Bearer {token}." }
```

Con token pero sin rol admin (403):

```json
{ "message": "No tienes permiso para realizar esta acción." }
```

Supervisor intentando entrar a un equipo de otro departamento (403):

```json
{ "message": "No tienes acceso a este departamento." }
```

---

## Reglas de negocio de la API privada

- `admin` → ve y edita los equipos de todos los departamentos, y es el único que puede
  borrar equipos y ver `/api/stats`.
- `supervisor` → sólo ve y edita los equipos de **su** `department_id`.
- Se reutiliza el método `User::canAccessDepartment()`, el mismo que usa la parte web,
  así que la API y el sitio no se contradicen.

---

## Antes de probar

```bash
php artisan migrate        # crea personal_access_tokens si aún no existe
php artisan route:list --path=api
php artisan optimize:clear
```
