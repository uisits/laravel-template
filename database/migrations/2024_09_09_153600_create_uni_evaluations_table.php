<?php

use App\Enums\Uni\Question10Options;
use App\Enums\Uni\Question11Options;
use App\Enums\Uni\Question12Options;
use App\Enums\Uni\Question1Options;
use App\Enums\Uni\Question2Options;
use App\Enums\Uni\Question3Options;
use App\Enums\Uni\Question4Options;
use App\Enums\Uni\Question5Options;
use App\Enums\Uni\Question6Options;
use App\Enums\Uni\Question7Options;
use App\Enums\Uni\Question8Options;
use App\Enums\Uni\Question9Options;
use App\Models\Registration;
use App\Models\User;
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
        Schema::create('uni_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Registration::class)->constrained();
            $table->enum('answer_1', Question1Options::values())->nullable();
            $table->enum('answer_2', Question2Options::values())->nullable();
            $table->enum('answer_3', Question3Options::values())->nullable();
            $table->enum('answer_4', Question4Options::values())->nullable();
            $table->enum('answer_5', Question5Options::values())->nullable();
            $table->enum('answer_6', Question6Options::values())->nullable();
            $table->enum('answer_7', Question7Options::values())->nullable();
            $table->enum('answer_8', Question8Options::values())->nullable();
            $table->enum('answer_9', Question9Options::values())->nullable();
            $table->enum('answer_10', Question10Options::values())->nullable();
            $table->enum('answer_11', Question11Options::values())->nullable();
            $table->enum('answer_12_1', Question12Options::values())->nullable();
            $table->enum('answer_12_2', Question12Options::values())->nullable();
            $table->enum('answer_12_3', Question12Options::values())->nullable();
            $table->enum('answer_12_4', Question12Options::values())->nullable();
            $table->enum('answer_12_5', Question12Options::values())->nullable();
            $table->enum('answer_12_6', Question12Options::values())->nullable();
            $table->longText('answer_13')->nullable();
            $table->longText('answer_14')->nullable();
            $table->longText('answer_15')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uni_evaluations');
    }
};
