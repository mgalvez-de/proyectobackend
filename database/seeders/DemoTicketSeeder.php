<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\MaintenanceType;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Tickets de ejemplo para que el historial, "Mis tickets" y "Mis reportes" no partan vacíos.
 * Solo se cargan si todavía no hay ningún ticket (no duplica ni toca datos reales).
 */
class DemoTicketSeeder extends Seeder
{
    public function run(): void
    {
        if (Ticket::exists()) {
            return;
        }

        $device = fn (string $name) => Device::where('name', $name)->value('id');
        $user = fn (string $rut) => User::where('rut', $rut)->value('id');
        $technician = fn (string $name) => Technician::where('name', $name)->value('id');
        $maintenance = fn (string $name) => MaintenanceType::where('name', $name)->value('id');

        // cuentas de UserSeeder / TechnicianSeeder
        $student = $user('222222222');
        $supervisor = $user('111111111');
        $carlosAccount = $user('555555555');
        $carlos = $technician('Carlos Muñoz');
        $valentina = $technician('Valentina Rojas');

        $tickets = [
            // reactivos (los reporta el estudiante con el QR)
            [
                'device_id' => $device('PC-INF-02'), 'user_id' => $student, 'type' => 'reactive', 'problem_type' => 'software',
                'error_description' => 'El sistema operativo no inicia', 'severity' => 'critical', 'status' => 'pending',
                'comments' => 'Se queda en la pantalla de carga', 'created_at' => now()->subHours(3),
            ],
            [
                'device_id' => $device('NB-INF-01'), 'user_id' => $student, 'type' => 'reactive', 'problem_type' => 'hardware',
                'error_description' => 'Teclado o mouse no responde', 'severity' => 'medium', 'status' => 'closed',
                'technician_id' => $carlos, 'solution' => 'Se reemplazó el mouse y se probó en otro puerto USB',
                'closed_at' => now()->subDays(2), 'closed_by' => $carlosAccount, 'created_at' => now()->subDays(4),
            ],
            [
                'device_id' => $device('PC-BIB-01'), 'user_id' => $student, 'type' => 'reactive', 'problem_type' => 'hardware',
                'error_description' => 'La pantalla no enciende', 'severity' => 'high', 'status' => 'in_progress',
                'technician_id' => $valentina, 'comments' => 'El monitor no da imagen', 'created_at' => now()->subDay(),
            ],
            [
                'device_id' => $device('PC-COM-01'), 'user_id' => $student, 'type' => 'reactive', 'problem_type' => 'software',
                'error_description' => 'Virus o malware detectado', 'severity' => 'high', 'status' => 'pending',
                'comments' => 'Aparecen ventanas de publicidad', 'created_at' => now()->subHours(6),
            ],

            // preventivas (las programa el encargado de Informática)
            [
                'device_id' => $device('PC-INF-01'), 'user_id' => $supervisor, 'type' => 'preventive',
                'maintenance_type_id' => $maintenance('Limpieza interna'), 'error_description' => 'Limpieza de ventiladores y polvo interno',
                'status' => 'pending', 'technician_id' => $carlos, 'scheduled_at' => now()->addDays(5)->setTime(10, 0),
                'created_at' => now()->subDay(),
            ],
            [
                'device_id' => $device('PC-INF-02'), 'user_id' => $supervisor, 'type' => 'preventive',
                'maintenance_type_id' => $maintenance('Actualización de software'), 'error_description' => 'Actualizar el sistema y el antivirus antes del semestre',
                'status' => 'completed', 'technician_id' => $carlos, 'scheduled_at' => now()->subDays(6)->setTime(9, 0),
                'solution' => 'Se instalaron las actualizaciones pendientes y se reinició el equipo',
                'closed_at' => now()->subDays(6), 'closed_by' => $carlosAccount, 'created_at' => now()->subDays(8),
            ],
        ];

        foreach ($tickets as $data) {
            if (! $data['device_id'] || ! $data['user_id']) {
                continue; // falta algún dato base (equipo o usuario)
            }

            $ticket = new Ticket($data);
            $ticket->created_at = $data['created_at'];
            $ticket->save();
        }
    }
}
