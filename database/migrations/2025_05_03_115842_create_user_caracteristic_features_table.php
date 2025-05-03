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
        Schema::create('user_caracteristic_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('question_one')->nullable();
            $table->longText('question_two')->nullable();
            $table->longText('question_three')->nullable();
            $table->longText('question_four')->nullable();
            $table->longText('question_five')->nullable();
            $table->longText('question_six')->nullable();
            $table->longText('question_seven')->nullable();
            $table->longText('question_eight')->nullable();
            $table->longText('question_nine')->nullable();
            $table->longText('question_ten')->nullable();
            $table->longText('question_eleven')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_caracteristic_features');
    }
};
