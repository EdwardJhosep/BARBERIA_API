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
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('servicio_id');
            $table->unsignedBigInteger('empleado_id')->nullable(); // New column
            $table->dateTime('fecha_hora');
            $table->decimal('precio_estimado', 10, 2);
            $table->string('codigo_unico');
            $table->text('qr_code')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('set null'); // Set null on delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas');
    }
}
