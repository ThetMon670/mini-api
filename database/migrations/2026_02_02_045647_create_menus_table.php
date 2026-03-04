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
        Schema::create('menus', function (Blueprint $table) {
            $table->id(); // Menu ID
            $table->string('title'); // Title of the menu item
            $table->string('slug')->unique(); // Slug for SEO-friendly URLs
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key to category
            $table->string('unit');
            $table->integer('price'); // Price of the menu item
            $table->string('image');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
