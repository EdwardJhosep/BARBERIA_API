<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasTable extends Migration
{
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id');
            $table->foreignId('servicio_id');
            $table->foreignId('empleado_id');
            $table->dateTime('fecha_hora');
            $table->decimal('precio_estimado', 10, 2);
            $table->uuid('codigo_unico')->unique();
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas');
    }
}

