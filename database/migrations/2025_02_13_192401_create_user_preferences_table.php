<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            Schema::create('user_preferences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->json('preferred_sources')->nullable(); // e.g., ["NewsAPI", "NYT"]
                $table->json('preferred_categories')->nullable(); // e.g., ["Technology", "Sports"]
                $table->json('preferred_authors')->nullable(); // e.g., ["John Doe"]
                $table->json('preferred_providers')->nullable();
                $table->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            Schema::dropIfExists('user_preferences');
        });
    }
};
