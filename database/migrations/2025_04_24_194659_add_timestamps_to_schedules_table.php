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
            $table->renameColumn('time', 'arrival_time');
            $table->string('departure_time')->after('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule', function (Blueprint $table) {
            $table->renameColumn('arrival_time', 'time');
            $table->dropColumn('departure_time');          
        });
    }
};
