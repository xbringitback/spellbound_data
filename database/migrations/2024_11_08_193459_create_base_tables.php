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
        // Houses table
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('house', 30);
            $table->string('emoji', 5);
            $table->string('founder', 30);
            $table->json('colors');
            $table->string('animal', 30);
            $table->integer('api_index')->unique(); 
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });

        // Characters table
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('fullName', 50);
            $table->string('nickname', 30);
            $table->string('interpretedBy', 30)->nullable();
            $table->json('children')->nullable();
            $table->string('image', 255);
            $table->string('birthdate', 20);
            $table->integer('api_index')->unique();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });

        // Spells table
        Schema::create('spells', function (Blueprint $table) {
            $table->id();
            $table->string('spell', 50);
            $table->text('use')->nullable();
            $table->integer('api_index')->unique();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spells');
        Schema::dropIfExists('characters');
        Schema::dropIfExists('houses');
    }
};
