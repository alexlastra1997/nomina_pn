<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('formularios', function (Blueprint $table) {
            $table->id();

            $table->string('cedula', 10)->unique();
            $table->string('apellido_paterno', 80);
            $table->string('apellido_materno', 80);
            $table->string('nombres', 120);

            $table->date('fecha_nacimiento');
            $table->string('estado_civil', 30);

            $table->unsignedSmallInteger('banco_id');
            $table->string('banco_nombre', 150);

            $table->enum('tipo_cuenta', ['AHORROS', 'CORRIENTE']);
            $table->string('cuenta', 30);

            $table->unsignedTinyInteger('escuela'); // 1..5
            $table->string('celular', 10);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formularios');
    }
};