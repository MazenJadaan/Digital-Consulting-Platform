<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->bigInteger('phone_num');
            $table->String('consulting_type')->nullable();
            $table->string('address')->nullable();
            $table->longText('bio')->nullable();
            $table->bigInteger('cost')->nullable();
            

            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->references('id')->on('wallets');

            $table->string('picture')->unique()->nullable();
            $table->integer('rate')->nullable();
            
            
            
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
        Schema::dropIfExists('experts');
    }
};
