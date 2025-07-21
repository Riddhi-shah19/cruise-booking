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
        Schema::table('schedule', function (Blueprint $table) {
            $table->renameColumn('train_id', 'cruise_id');
            $table->renameColumn('first_fee', 'luxury_fee');
            $table->renameColumn('second_fee', 'general_fee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->renameColumn('cruise_id', 'train_id');
            $table->renameColumn('luxury_fee', 'first_fee');
            $table->renameColumn('general_fee', 'second_fee');
            $table->dropTimestamps();
        });
    }
};
