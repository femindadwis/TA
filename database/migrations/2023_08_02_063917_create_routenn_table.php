<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Driver;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routenn', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Driver::class);
            $table->string('urutan');
            $table->string('jarak');
            $table->timestamps();
            $table->foreign('driver_id')->references('id')->on('driver')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routenn');
    }
};
