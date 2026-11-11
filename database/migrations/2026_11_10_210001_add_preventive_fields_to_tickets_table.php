<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('maintenance_type_id')
                ->nullable()
                ->after('problem_type')
                ->constrained('maintenance_types')
                ->nullOnDelete();

            $table->dateTime('closed_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['maintenance_type_id']);
            $table->dropColumn(['maintenance_type_id', 'closed_at']);
        });
    }
};
