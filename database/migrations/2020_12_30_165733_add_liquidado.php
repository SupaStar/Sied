<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLiquidado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::table('amortizaciones', function (Blueprint $table) {
             $table->integer('liquidado')->nullable()->default(0);
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::table('amortizaciones', function (Blueprint $table) {
           $table->dropColumn('liquidado');
         });
     }
}
