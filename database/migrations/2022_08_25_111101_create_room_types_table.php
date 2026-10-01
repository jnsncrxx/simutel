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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('room_name')->unique();
            $table->longText('views')->nullable();
            $table->decimal('rent', 10, 2);
            $table->string('status');
            $table->integer('default_occupancy');
            $table->integer('max_occupancy');
            $table->decimal('extra_adult', 10, 2);
            $table->longText('description');
            $table->longText('beds');
            $table->longText('amenities');
            $table->integer('points');
            $table->integer('points_required');
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
        Schema::dropIfExists('room_types');
    }
};
