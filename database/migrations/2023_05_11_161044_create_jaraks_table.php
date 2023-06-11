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
        Schema::create('jaraks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loc_1');
            $table->unsignedBigInteger('loc_2');
            $table->string('distance');
            $table->timestamps();

            $table->foreign('loc_1')->references('id')->on('lokasi')->onDelete('cascade');
            $table->foreign('loc_2')->references('id')->on('lokasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jaraks');
    }
};
