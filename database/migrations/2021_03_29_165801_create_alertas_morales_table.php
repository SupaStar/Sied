<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertasMoralesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('alertas_morales', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('moral_id');
      $table->unsignedBigInteger('credito_moral_id');
      $table->string('tipo_alerta', 191);
      $table->text('titulo');
      $table->text('descripcion');
      $table->integer('estatus');
      $table->text('observacion');
      $table->text('prioridad');
      $table->text('sustento')->nullable();
      $table->string('archivo_sustento', 100)->nullable();
      $table->text('dictamen')->nullable();
      $table->string('archivo_dictamen', 100)->nullable();
      $table->text('acuse')->nullable();
      $table->string('archivo_acuse', 100)->nullable();
      $table->integer('envio')->default(0);
      $table->timestamps();
    });
    Schema::table('pago_morales', function (Blueprint $table) {
      $table->dropColumn('forma');
    });
    Schema::table('pago_morales', function (Blueprint $table) {
      $table->string('forma', 191);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('alertas_morales');
  }
}
