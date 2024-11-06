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
        Schema::create('bookracks', function (Blueprint $table) {
            $table->id();
            $table->string('rack_layer')->nullable();
            $table->string('floor')->nullable();  // Location like 'R1C3'
            $table->string('range', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookracks');
    }
};
