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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // This will create an auto-incrementing bigint id.
            $table->string('title'); // Title of the category
            $table->string('slug')->unique(); // Slug for SEO-friendly URLs
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Linking to users table
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
