<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('riesgo')->nullable();
            $table->decimal('minimo',10,2)->nullable();
            $table->decimal('maximo',10,2)->nullable();
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
        Schema::dropIfExists('factores');
    }
}
