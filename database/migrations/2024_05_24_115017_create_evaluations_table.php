<?php

use App\Enums\Evaluations\Question1Options;
use App\Enums\Evaluations\Question2Options;
use App\Enums\Evaluations\Question3Options;
use App\Enums\Evaluations\Question4Options;
use App\Enums\Evaluations\Question5Options;
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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Registration::class)->constrained();
            $table->enum('answer_1', Question1Options::values())->nullable();
            $table->enum('answer_2', Question2Options::values())->nullable();
            $table->enum('answer_3', Question3Options::values())->nullable();
            $table->enum('answer_4', Question4Options::values())->nullable();
            $table->text('answer_5')->nullable();
            $table->enum('answer_6', [1,2,3,4,5])->nullable();
            $table->enum('answer_7', [1,2,3,4,5])->nullable();
            $table->enum('answer_8', [1,2,3,4,5])->nullable();
            $table->enum('answer_9', [1,2,3,4,5])->nullable();
            $table->enum('answer_10', [1,2,3,4,5])->nullable();
            $table->enum('answer_11', [1,2,3,4,5])->nullable();
            $table->enum('answer_12', [1,2,3,4,5])->nullable();
            $table->enum('answer_13', [1,2,3,4,5])->nullable();
            $table->enum('answer_14', [1,2,3,4,5])->nullable();
            $table->enum('answer_15', [1,2,3,4,5])->nullable();
            $table->longText('answer_16')->nullable();
            $table->longText('answer_17')->nullable();
            $table->longText('answer_18')->nullable();
            $table->longText('answer_19')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
