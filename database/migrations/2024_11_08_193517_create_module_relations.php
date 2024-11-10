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
        // (n:1)
        Schema::table('characters', function (Blueprint $table) {
            $table->foreignId('house_id')->nullable()->constrained('houses')->nullOnDelete();
        });

        // (n:m)
        Schema::create('character_spell', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spell_id')->constrained()->cascadeOnDelete();
            // API spezifische Felder
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    // First drop pivot-table 
    Schema::dropIfExists('character_spell');
    
    // Then remove foreign key aus 
    Schema::table('characters', function (Blueprint $table) {
        $table->dropForeign(['house_id']);
        $table->dropColumn('house_id');
    });
    }
};
