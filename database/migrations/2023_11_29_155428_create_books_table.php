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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('code', 10);
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');
            $table->foreignId('publisher_id')->constrained('publishers')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('book_locations')->onDelete('cascade');
            $table->integer('pages')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamp('published_year')->nullable();
            $table->mediumText('description')->nullable();
            $table->integer('fee_per_day');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
