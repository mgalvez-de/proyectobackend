# Gestión de equipos tecnológicos UCSC

Plataforma web para llevar el inventario de los equipos tecnológicos de la UCSC (Informática, Biblioteca y Sala de Computación) y seguir cada falla desde que alguien la reporta con el código QR del equipo hasta que un técnico la repara.

Proyecto semestral de **Programación Backend (IT1030B)** · Ingeniería de Ejecución en Informática · Docente: Felipe Valentín.
Equipo: Diego Bustos, Matías Gálvez, Teresa Cartes y Matías Jerez.

## Qué hace

| Perfil | Qué puede hacer |
|---|---|
| Administrador | Ve los tres departamentos, administra usuarios, técnicos y todos los mantenedores (marcas, tipos, RAM, almacenamiento, procesadores, placas madre, estados, fallas). |
| Supervisor (encargado) | Trabaja solo con su departamento: equipos, estado de cada equipo, historial, asignación de técnicos, mantenciones preventivas e historial de cada equipo en PDF. |
| Técnico | Ve sus tickets asignados, los pasa a "en proceso" y los cierra escribiendo la solución. |
| Estudiante | Reporta fallas escaneando el código QR del equipo y sigue sus reportes en "Mis reportes". |

También tiene una API REST (pública para RAM y procesadores, privada con token de Sanctum para equipos y estadísticas), documentada dentro de la plataforma en **Desarrollo → Documentación API**.

## Requisitos

- PHP 8.2+, Composer, Node.js y MySQL (por ejemplo con XAMPP).

## Instalación

```bash
git clone https://github.com/mgalvez-de/proyectobackend.git
cd proyectobackend
composer install
npm install
cp .env.example .env
php artisan key:generate
```

En `.env` se configura la base de datos (por defecto MySQL, base `laravel`, usuario `root`). Con MySQL encendido:

```bash
php artisan migrate --seed   # crea las tablas, los mantenedores, 9 equipos, usuarios y tickets de ejemplo
npm run build                # o dejar "npm run dev" abierto mientras se trabaja
php artisan serve
```

Entrar a http://127.0.0.1:8000. Las cuentas de prueba (una por perfil) están en `database/seeders/UserSeeder.php` y `database/seeders/TechnicianSeeder.php`; el inicio de sesión es con el **RUT sin puntos ni guion**. No hay registro público: las cuentas nuevas las crea el administrador.

Para que el código QR funcione desde un celular, el celular debe estar en la misma red que el computador. La dirección que lleva el QR se puede fijar con `QR_BASE_URL` en `.env` (por ejemplo `http://192.168.1.10:8000`); si se deja vacía, el QR usa la misma dirección con la que se abrió la página.

## Pruebas

```bash
php artisan test
```

Las pruebas de la API están en `test_api.ipynb` (Python) y en la colección de Postman que se descarga desde la Documentación API.

## Documentación

Manual de Usuario, Manual Técnico y Especificación de Requisitos (entregados junto al proyecto), y los diagramas en `docs/diagramas`.
