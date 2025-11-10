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
        // Check if email column exists before dropping it
        if (Schema::hasColumn('employees', 'email')) {
            // First drop the unique index if it exists
            if (Schema::hasIndex('employees', 'employees_email_unique')) {
                Schema::table('employees', function (Blueprint $table) {
                    $table->dropIndex('employees_email_unique');
                });
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
