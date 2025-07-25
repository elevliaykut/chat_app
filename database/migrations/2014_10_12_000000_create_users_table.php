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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('age')->nullable();
            $table->string('token')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->string('tckn')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->timestamp('birth_date')->nullable();
            $table->boolean('liked_by_me')->default(false);
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('favorite_count')->default(0);
            $table->unsignedInteger('smile_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
