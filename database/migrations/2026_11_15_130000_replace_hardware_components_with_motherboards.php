<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Se quita el mantenedor de componentes y se reemplaza por el de placas madre
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('hardware_components');

        Schema::create('motherboards', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motherboards');
    }
};
