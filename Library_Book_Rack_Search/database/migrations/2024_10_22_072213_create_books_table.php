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
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('book_rack_id')->nullable()->constrained('book_racks')->onDelete('set null');
            $table->string('rack_layer')->nullable();
            $table->string('floor')->nullable();  // Location like 'R1C3'
            $table->string('location_id')->nullable();
            $table->string('image')->nullable(); // Nullable image field
            $table->string('title')->nullable();// Title with max 50 characters
            $table->string('author')->nullable(); // Author with max 50 characters
            $table->string('description')->nullable();// Description with max 100 characters
            $table->boolean('is_available')->default(true); // Availability (Boolean)
            $table->integer('duration')->unsigned()->nullable(); // Duration in days
            $table->timestamps();// created_at and updated_at
            $table->softDeletes(); // For deleted_at column
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
