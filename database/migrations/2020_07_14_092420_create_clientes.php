<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('name')->nullable();
          $table->string('lastname')->nullable();
          $table->string('o_lastname')->nullable();
          $table->string('gender')->nullable();
          $table->date('date_birth')->nullable();
          $table->string('country_birth')->nullable();
          $table->string('nationality')->nullable();
          $table->text('place_birth')->nullable();
          $table->string('job')->nullable();

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

          $table->string('c_name')->nullable();
          $table->string('c_lastname')->nullable();
          $table->string('c_o_lastname')->nullable();
          $table->string('c_phone')->nullable();
          $table->string('c_email')->nullable();

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
        Schema::dropIfExists('clientes');
    }
}
