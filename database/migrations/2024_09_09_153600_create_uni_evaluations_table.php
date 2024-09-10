<?php

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
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Registration::class)->constrained();
            $table->string('answer_1')->nullable();
            $table->string('answer_2')->nullable();
            $table->string('answer_3')->nullable();
            $table->string('answer_4')->nullable();
            $table->string('answer_5')->nullable();
            $table->string('answer_6')->nullable();
            $table->string('answer_7')->nullable();
            $table->string('answer_8')->nullable();
            $table->string('answer_9')->nullable();
            $table->string('answer_10')->nullable();
            $table->string('answer_11')->nullable();
            $table->string('answer_12_1')->nullable();
            $table->string('answer_12_2')->nullable();
            $table->string('answer_12_3')->nullable();
            $table->string('answer_12_4')->nullable();
            $table->string('answer_12_5')->nullable();
            $table->string('answer_12_6')->nullable();
            $table->string('answer_13')->nullable();
            $table->string('answer_14')->nullable();
            $table->string('answer_15')->nullable();
            $table->longText('comment')->nullable();
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