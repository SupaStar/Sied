<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDecimalesConfiguracion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('configuracionalerta', function (Blueprint $table) {
        $table->dropColumn("valor");
        $table->dropColumn("tiie28");
        $table->dropColumn("fix");
        $table->dropColumn("cetes28");
      });
      Schema::table('configuracionalerta', function (Blueprint $table) {
        $table->decimal("valor",11,6);
        $table->decimal("tiie28",11,6);
        $table->decimal("fix",11,6);
        $table->decimal("cetes28",11,6);
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
