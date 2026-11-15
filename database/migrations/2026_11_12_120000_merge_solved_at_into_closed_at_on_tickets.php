<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Un solo registro de cierre: closed_at + closed_by (+ solution).
// solved_at hacía lo mismo que closed_at, así que se traspasa y se elimina.
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('tickets', 'solved_at')) {
            return;
        }

        DB::table('tickets')
            ->whereNull('closed_at')
            ->whereNotNull('solved_at')
            ->update(['closed_at' => DB::raw('solved_at')]);

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('solved_at');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dateTime('solved_at')->nullable()->after('solution');
        });
    }
};
