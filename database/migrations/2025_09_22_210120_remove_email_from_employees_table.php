<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if email column exists before dropping it
        if (Schema::hasColumn('employees', 'email')) {
            // PostgreSQL: drop constraint; SQLite/MySQL: drop index
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::statement('ALTER TABLE employees DROP CONSTRAINT IF EXISTS employees_email_unique');
            } else {
                try {
                    Schema::table('employees', function (Blueprint $table) {
                        if (Schema::hasIndex('employees', 'employees_email_unique')) {
                            $table->dropIndex('employees_email_unique');
                        }
                    });
                } catch (\Exception $e) {
                    // Index may not exist, continue
                }
            }

            // Then drop the column
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('email')->nullable()->unique()->after('position');
        });
    }
};
