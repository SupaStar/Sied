<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('morales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre')->nullable();
            $table->string('street')->nullable();
            $table->string('exterior')->nullable();
            $table->string('inside')->nullable();
            $table->string('pc')->nullable();
            $table->string('colony')->nullable();
            $table->string('town')->nullable();
            $table->string('city')->nullable();
            $table->string('ef')->nullable();
            $table->string('country')->nullable();

            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('email')->nullable();
            $table->string('curp')->nullable();
            $table->string('rfc')->nullable();

            $table->string('status')->default('');

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
        Schema::dropIfExists('morales');
    }
}
