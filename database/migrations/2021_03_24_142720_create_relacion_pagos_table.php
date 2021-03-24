<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelacionPagosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('relacion_pagos_morales', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('amortizacion_moral_id');
      $table->unsignedBigInteger('pago_moral_id');
      $table->date('fecha_pago');
      $table->decimal('monto', 10, 2);
      $table->decimal('monto_total', 10, 2);
      $table->decimal('restante', 10, 2);
      $table->decimal('pago_restante', 10, 2);
      $table->text('descripcion');
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
