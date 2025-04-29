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
        Schema::create('user_spouse_candidate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('age_range')->nullable(); // yaş araılığı
            $table->tinyInteger('marital_status')->nullable(); // medeni hali
            $table->string('have_a_child')->nullable(); // çocuğu olsunmu
            $table->string('use_cigarette')->nullable(); // sigara kullansınmı
            $table->string('use_alcohol')->nullable(); // Alkol kullansınmı
            $table->string('education_status')->nullable(); // Eğitim Durumu
            $table->string('salary')->nullable(); // Maaşı
            $table->string('not_compromise')->nullable(); // Taviz vermeyecekleriniz
            $table->string('community')->nullable(); // Cemaat
            $table->string('sect')->nullable(); // Mezhep
            $table->string('do_you_pray')->nullable(); // Namaz kılsınmı
            $table->string('physical_disability')->nullable(); //Fiziksel Engel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_spouse_candidate');
    }
};
