<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('servidores', function (Blueprint $table) {
            $table->id();
            $table->string('cedula', 10)->unique();
            $table->string('apellidos', 200);
            $table->string('nombres', 200);
            $table->timestamps();

            $table->index('cedula');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servidores');
    }
};