<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorialFlujos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_flujos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('periodo_id');
            $table->decimal('monto',10,2)->nullable();
            $table->decimal('cambio',10,2)->nullable();
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_flujos');
    }
}
