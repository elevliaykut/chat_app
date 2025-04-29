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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('profile_summary')->nullable();
            $table->longText('biography')->nullable();
            $table->text('horoscope')->nullable();
            $table->tinyInteger('city_id')->nullable();
            $table->tinyInteger('district_id')->nullable();
            $table->tinyInteger('marital_status')->nullable();
            $table->tinyInteger('online_status')->nullable();
            $table->string('headscarf')->nullable();
            $table->string('tall')->nullable();
            $table->string('weight')->nullable();
            $table->string('eye_color')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('skin_color')->nullable();
            $table->string('body_type')->nullable();
            $table->string('have_a_child')->nullable();
            $table->string('want_a_child')->nullable();
            $table->string('use_cigarette')->nullable();
            $table->string('use_alcohol')->nullable();
            $table->string('education_status')->nullable();
            $table->string('foreign_language')->nullable();
            $table->string('job')->nullable();
            $table->string('salary')->nullable();
            $table->string('work_status')->nullable();
            $table->string('live_with')->nullable();
            $table->string('clothing_style')->nullable();
            $table->string('not_compromise')->nullable();
            $table->string('community')->nullable();
            $table->string('sect')->nullable();
            $table->string('do_you_pray')->nullable();
            $table->string('do_you_know_quran')->nullable();
            $table->string('are_you_fasting')->nullable();
            $table->string('consider_important_in_islam')->nullable();
            $table->string('listening_music_types')->nullable();
            $table->string('reading_book_types')->nullable();
            $table->string('looking_qualities')->nullable();
            $table->string('your_hobbies')->nullable();
            $table->string('your_personality')->nullable();
            $table->string('physical_disability')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
