<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPagosMoralesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('pago_morales', function (Blueprint $table) {
        $table->dropColumn('pago');
        $table->dropColumn('fpago');
        $table->dropColumn('periodo');
        $table->dropColumn('moneda');
        $table->dropColumn('origen');
        $table->dropColumn('forma');
      });
      Schema::table('pago_morales', function (Blueprint $table) {
        $table->decimal('pago', 10,2)->nullable();
        $table->date('fpago')->nullable();
        $table->integer('periodo')->nullable();
        $table->string('moneda', 191)->nullable();
        $table->string('origen', 191)->nullable();
        $table->decimal('forma', 10, 2)->nullable();
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
