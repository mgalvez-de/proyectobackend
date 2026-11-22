<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Estado de cada equipo: operativo, en mantención o dado de baja (catálogo device_statuses)
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (! Schema::hasColumn('devices', 'device_status_id')) {
                $table->foreignId('device_status_id')->nullable()->after('device_type_id')
                    ->constrained('device_statuses')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (Schema::hasColumn('devices', 'device_status_id')) {
                $table->dropConstrainedForeignId('device_status_id');
            }
        });
    }
};
