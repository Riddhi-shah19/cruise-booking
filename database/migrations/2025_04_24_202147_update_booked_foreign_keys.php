<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booked', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('cruise_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('user_id');
            $table->string('code');
            $table->date('date');
            $table->integer('rooms_booked');
            $table->timestamps();

            $table->foreign('schedule_id')->references('id')->on('schedule')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payment')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('passenger')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('cruise_id')->references('id')->on('cruise')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booked', function (Blueprint $table) {
            //
        });
    }
};
