<?php

use App\Models\Jeniskendaraan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
            $table->foreignIdFor(User::class);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('username')->unique();
            $table->foreignIdFor(Jeniskendaraan::class);
            $table->foreign('jeniskendaraan_id')->references('id')->on('jeniskendaraan')->onDelete('cascade');
            $table->string('alamat');
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
        Schema::dropIfExists('driver');
    }
};
