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
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('semester', 5)->unique();
            $table->string('semester_description')->nullable();
            $table->boolean('blessed')->default(false);
            $table->boolean('roll_up')->default(false);
            $table->boolean('active')->default(false);
            $table->dateTime('evaluation_start')->nullable();
            $table->dateTime('evaluation_end')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
