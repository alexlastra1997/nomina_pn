<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('formularios', function (Blueprint $table) {
            // Si era integer, ahora string
            $table->string('escuela', 200)->change();
        });
    }

    public function down(): void
    {
        Schema::table('formularios', function (Blueprint $table) {
            // vuelve a integer si algún día necesitas revertir
            $table->integer('escuela')->change();
        });
    }
};