<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// El formulario de dispositivos pide procesador y tipo, pero la tabla no tenía esas columnas
// (al crear o editar un equipo salía un error de SQL). Quedan opcionales para los equipos ya registrados.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (! Schema::hasColumn('devices', 'processor_id')) {
                $table->foreignId('processor_id')->nullable()->after('ram_id')
                    ->constrained('processors')->nullOnDelete();
            }

            if (! Schema::hasColumn('devices', 'device_type_id')) {
                $table->foreignId('device_type_id')->nullable()->after('processor_id')
                    ->constrained('device_types')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (Schema::hasColumn('devices', 'device_type_id')) {
                $table->dropConstrainedForeignId('device_type_id');
            }

            if (Schema::hasColumn('devices', 'processor_id')) {
                $table->dropConstrainedForeignId('processor_id');
            }
        });
    }
};
