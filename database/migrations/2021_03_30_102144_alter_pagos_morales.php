<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPagosMorales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('pago_morales', function (Blueprint $table) {
        $table->dropColumn('moneda');
        $table->dropColumn('forma');
      });
      Schema::table('pago_morales', function (Blueprint $table) {
        $table->string('moneda', 191)->nullable();
        $table->string('forma', 191)->nullable();
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
