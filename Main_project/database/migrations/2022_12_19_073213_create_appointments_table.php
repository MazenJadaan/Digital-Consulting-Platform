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
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('expert_id');
            $table->date('date_appointment');
            $table->time('start_hour');
            $table->time('end_hour');
          //  $table->enum('time',['AM','PM']);
            $table->boolean('booked_appointments')->default(false);
         

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('expert_id')->references('id')->on('experts');

            
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
        Schema::dropIfExists('appointments');
    }
};
