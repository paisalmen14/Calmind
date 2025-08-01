<?php

// database/migrations/YYYY_MM_DD_XXXXXX_create_stories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id(); //
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //
            $table->text('content'); //
            $table->boolean('is_anonymous')->default(false); //
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};