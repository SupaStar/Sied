<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGcobranza extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::table('amortizaciones', function (Blueprint $table) {
             $table->decimal('gcobranza',10,2)->nullable();
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
           $table->dropColumn('gcobranza');
         });
     }
}
