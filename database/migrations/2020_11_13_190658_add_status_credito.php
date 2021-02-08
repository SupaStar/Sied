<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusCredito extends Migration
{
  public function up()
  {
      Schema::table('credito', function (Blueprint $table) {
          $table->string('status')->nullable()->default('Aprobado');
      });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      Schema::table('credito', function (Blueprint $table) {
          $table->dropColumn('status');
      });
  }
}
