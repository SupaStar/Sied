<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPagos extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
   public function up()
   {
       Schema::table('pagos', function (Blueprint $table) {
           $table->Integer('periodo')->nullable();
       });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
       Schema::table('pagos', function (Blueprint $table) {
           $table->dropColumn('periodo');
       });
   }
}
