<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLimiteCredito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('perfil_transacional_moral', function (Blueprint $table) {
        $table->decimal('limite_credito',10,2)->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perfil_transacional_moral', function (Blueprint $table) {
            $table->dropColumn('limite_credito');
          });
      }
}
