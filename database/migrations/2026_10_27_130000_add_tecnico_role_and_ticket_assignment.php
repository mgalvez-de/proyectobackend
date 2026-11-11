<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // nuevo rol "tecnico"
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['admin', 'supervisor', 'estudiante', 'tecnico'])
                ->default('estudiante')
                ->change();
        });

        // cuenta de usuario del técnico (opcional)
        Schema::table('technicians', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->after('department_id')
                ->constrained('users')
                ->nullOnDelete();
        });

        // técnico encargado y solución del ticket
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('technician_id')
                ->nullable()
                ->after('user_id')
                ->constrained('technicians')
                ->nullOnDelete();
            $table->text('solution')->nullable()->after('comments');
            $table->dateTime('solved_at')->nullable()->after('solution');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('technician_id');
            $table->dropColumn(['solution', 'solved_at']);
        });

        Schema::table('technicians', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['admin', 'supervisor', 'estudiante'])
                ->default('estudiante')
                ->change();
        });
    }
};
