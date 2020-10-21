<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->text('poll_description');
            $table->integer('views')->default(0);
            $table->timestamps();
        });

        Schema::create('options', function (Blueprint $table) {
           $table->id();
           $table->text('option_description');
           $table->unsignedBigInteger('poll_id');
           $table->foreign('poll_id')->references('id')->on('polls');
           $table->timestamps();
        });

        Schema::create('votes', function (Blueprint $table) {
            $table->unsignedBigInteger('option_id');
            $table->foreign('option_id')->references('id')->on('options');
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
        Schema::dropIfExists('votes');
        Schema::dropIfExists('options');
        Schema::dropIfExists('polls');
    }
}
