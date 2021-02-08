<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationPagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relacion_pagos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('periodo_id');
            $table->bigInteger('pago_id');
            $table->date('fecha_pago')->nullable();
            $table->decimal('monto',10,2)->nullable();
            $table->decimal('monto_total',10,2)->nullable();
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
        Schema::dropIfExists('relacion_pagos');
    }
}
