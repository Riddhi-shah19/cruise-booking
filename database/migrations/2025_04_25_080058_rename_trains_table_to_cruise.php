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
        // Schema::rename('train', 'cruise');
        Schema::table('cruise', function (Blueprint $table) {
            $table->renameColumn('general_seats', 'general_rooms');
            $table->renameColumn('luxury_seats', 'luxury_rooms');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            // Schema::rename('cruise', 'train');
            Schema::table('train', function (Blueprint $table) {
                $table->renameColumn('general_seats', 'first_seat');
                $table->renameColumn('luxury_seats', 'second_seat');
            });
    }
};
