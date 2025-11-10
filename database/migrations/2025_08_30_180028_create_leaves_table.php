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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id')->unsigned();
            $table->date('leave_date');
            $table->tinyInteger('status')->default(0); // 0 = pending, 1 = approved
            $table->string('reason')->nullable();
            $table->string('leave_type')->nullable(); // e.g., sick leave, vacation
            $table->string('duration')->nullable(); // e.g., half day, full day
            
            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropForeign(['emp_id']);
        });
        
        Schema::dropIfExists('leaves');
    }
};
