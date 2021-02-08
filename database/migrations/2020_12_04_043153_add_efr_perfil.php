<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEfrPerfil extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
   public function up()
   {
       Schema::table('perfil_transacional', function (Blueprint $table) {
           $table->bigInteger('efr')->nullable();
       });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
       Schema::table('perfil_transacional', function (Blueprint $table) {
           $table->dropColumn('efr');
       });
   }
}
