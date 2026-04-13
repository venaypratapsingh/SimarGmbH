<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all attendance records with non-empty absence_reason
        $attendances = Attendance::whereNotNull('absence_reason')
            ->where('absence_reason', '!=', '')
            ->get();

        foreach ($attendances as $attendance) {
            $reason = $attendance->absence_reason;
            
            // Split by newline, comma, or whitespace and get the first code
            $codes = preg_split('/[\n,\s]+/', trim($reason), -1, PREG_SPLIT_NO_EMPTY);
            $firstCode = $codes[0] ?? null;
            
            // Only update if different (to avoid unnecessary updates)
            if ($firstCode && $firstCode !== $reason) {
                $attendance->update(['absence_reason' => $firstCode]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse action needed for data cleanup
    }
};

