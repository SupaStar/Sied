<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertasPld extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('alertas_pld', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('cliente_id');
      $table->bigInteger('credito_id');
      $table->string('tipo_alerta')->nullable();
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
    Schema::dropIfExists('alertas_pld');
  }
}
