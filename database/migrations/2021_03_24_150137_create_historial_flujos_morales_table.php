<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorialFlujosMoralesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_flujos_morales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('amortizacion_id');
            $table->decimal('monto',10,2);
            $table->decimal('cambio',10,2);
            $table->string('descripcion');
            $table->foreign('amortizacion_id')->references('id')->on('amortizaciones_morales');
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
        Schema::dropIfExists('historial_flujos_morales');
    }
}
