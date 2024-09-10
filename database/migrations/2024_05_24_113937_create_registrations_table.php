<?php

use App\Enums\EvaluationType;
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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('student_firstname')->nullable();
            $table->string('student_lastname');
            $table->string('student_netid');
            $table->string('course_no');
            $table->string('course_name');
            $table->boolean('is_uni_301')->virtualAs("course_no like 'UNI 301%'");
            $table->string('instructor_name');
            $table->string('instructor_uin', 9);
            $table->string('instructor_netid');
            $table->enum('section_type', [
                EvaluationType::LECTURE->value, EvaluationType::ONLINE->value,
                EvaluationType::CONFERENCE->value, EvaluationType::LAB->value,
                EvaluationType::UNKNOWN->value
            ]);
            $table->boolean('online');
            $table->string('semester', 5);
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
        Schema::dropIfExists('registrations');
    }
};
