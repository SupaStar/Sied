<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePonderacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ponderacion', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('factor')->nullable();
          $table->decimal('ponderacion',10,2)->nullable();
          $table->string('tipo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ponderacion');
    }
}
