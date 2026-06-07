<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->json('ingredients');         // array of ingredient strings
            $table->string('cook_time')->nullable();
            $table->string('emoji', 10)->default('🍽️');
            $table->string('image_path')->nullable();

            // FK to users — if user is deleted, keep recipe (set null) or reassign
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // FK to categories:
            // On category delete → set to the "uncategorized" category (handled in CategoryController)
            // We use nullable + restrict at DB level, logic is in app layer
            $table->foreignId('category_id')
                  ->constrained()
                  ->restrictOnDelete(); // prevent accidental direct DB deletes bypassing app logic

            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
