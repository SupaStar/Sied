<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAlertaAddobservaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('alertas_pld', function (Blueprint $table) {
        $table->text('sustento')->nullable();
        $table->string('archivo_sustento',100)->nullable();
        $table->text('dictamen')->nullable();
        $table->string('archivo_dictamen',100)->nullable();
        $table->text('acuse')->nullable();
        $table->string('archivo_acuse',100)->nullable();
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
