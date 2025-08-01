<?php

// database/migrations/YYYY_MM_DD_XXXXXX_create_articles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id(); //
            $table->string('title'); //
            $table->text('content'); //
            $table->string('image_path')->nullable(); //
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};