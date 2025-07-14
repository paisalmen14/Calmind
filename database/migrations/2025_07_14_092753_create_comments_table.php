<?php

// database/migrations/YYYY_MM_DD_XXXXXX_create_comments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); //
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //
            $table->foreignId('story_id')->constrained()->onDelete('cascade'); //
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade')->after('story_id'); //
            $table->text('content'); //
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};