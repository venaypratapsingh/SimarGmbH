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
        // Add break times to schedules table
        Schema::table('schedules', function (Blueprint $table) {
            $table->time('break_start')->nullable()->after('time_out');
            $table->time('break_end')->nullable()->after('break_start');
        });

        // Add break times to attendances table
        Schema::table('attendances', function (Blueprint $table) {
            $table->time('break_start')->nullable()->after('attendance_time');
            $table->time('break_end')->nullable()->after('break_start');
            $table->time('time_in')->nullable()->after('break_end');
            $table->time('time_out')->nullable()->after('time_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['break_start', 'break_end']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['break_start', 'break_end', 'time_in', 'time_out']);
        });
    }
};
