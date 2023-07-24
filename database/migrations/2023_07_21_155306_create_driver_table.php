<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Jeniskendaraan;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('no_polisi');
            $table->string('no_telepon');
            $table->string('username')->unique();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Jeniskendaraan::class);
            $table->string('alamat');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jeniskendaraan_id')->references('id')->on('jeniskendaraan')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver');
    }
};
