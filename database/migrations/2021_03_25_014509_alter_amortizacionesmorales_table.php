<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAmortizacionesmoralesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('amortizaciones_morales', function (Blueprint $table) {
      $table->dropColumn('fechas');
      $table->dropColumn('inicio');
      $table->dropColumn('fin');
      $table->dropColumn('dias');
      $table->dropColumn('disposicion');
      $table->dropColumn('saldo_insoluto');
      $table->dropColumn('comision');
      $table->dropColumn('amortizacion');
      $table->dropColumn('intereses');
      $table->dropColumn('moratorios');
      $table->dropColumn('iva');
      $table->dropColumn('flujo');
      $table->dropColumn('dias_mora');
      $table->dropColumn('int_mora');
      $table->dropColumn('pagos');
      $table->dropColumn('gcobranza');
      $table->dropColumn('iva_mora');
      $table->dropColumn('dia_mora');
    });
    Schema::table('amortizaciones_morales', function (Blueprint $table) {
      $table->string('fechas', 191)->nullable();
      $table->date('inicio')->nullable();
      $table->date('fin')->nullable();
      $table->integer('dias')->nullable();
      $table->decimal('disposicion', 10)->nullable();
      $table->decimal('saldo_insoluto', 10)->nullable();
      $table->decimal('comision', 10)->nullable();
      $table->decimal('amortizacion', 10)->nullable();
      $table->decimal('intereses', 10)->nullable();
      $table->decimal('moratorios', 10)->nullable();
      $table->decimal('iva', 10)->nullable();
      $table->decimal('flujo', 10)->nullable();
      $table->integer('dias_mora')->nullable();
      $table->decimal('int_mora', 10)->nullable();
      $table->decimal('pagos', 10)->nullable();
      $table->decimal('gcobranza', 10, 2)->nullable();
      $table->decimal('iva_mora', 10, 2)->nullable();
      $table->date('dia_mora')->nullable();
      $table->decimal('iva_cobranza',10,2)->nullable();
    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    //
  }
}
