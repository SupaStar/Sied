<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprobantePagosMoralesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobante_pagos_morales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pago_moral_id');
            $table->text('path');
            $table->string('extension',191);
            $table->text('name');
            $table->text('full');
            $table->integer('user_id');
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
        Schema::dropIfExists('comprobante_pagos_morales');
    }
}
