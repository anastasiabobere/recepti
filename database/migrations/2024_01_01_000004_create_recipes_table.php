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
        $table->json('ingredients');
        $table->string('cook_time')->nullable();
        $table->string('image_path')->nullable();
        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();
        $table->foreignId('category_id')
              ->constrained()
              ->restrictOnDelete();
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
