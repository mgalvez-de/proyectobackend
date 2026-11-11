<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hardware_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnDelete();
            $table->string('type');
            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->nullOnDelete();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable()->unique();
            $table->date('installed_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hardware_components');
    }
};
