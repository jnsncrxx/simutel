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
        Schema::create('room_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserved_by_guest_id')->references('id')->on('guests')->onDelete('cascade');
            $table->foreignId('payer_guest_id')->references('id')->on('guests')->onDelete('cascade');
            $table->integer('room_no')->nullable();
            $table->string('room_type');
            $table->integer('adults')->default(0);
            $table->integer('children')->default(0);
            $table->datetime('check_in');
            $table->datetime('check_out');
            $table->string('rate');
            $table->decimal('amount', 10, 2);
            $table->longText('requests')->nullable();
            $table->string('source');
            $table->string('status')->default('Confirmed');
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
        Schema::dropIfExists('room_reservations');
    }
};
