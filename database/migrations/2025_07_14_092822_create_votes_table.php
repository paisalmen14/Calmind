<?php

// database/migrations/YYYY_MM_DD_XXXXXX_create_votes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id(); //
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //
            $table->foreignId('story_id')->constrained()->onDelete('cascade'); //
            $table->smallInteger('vote'); // 1 untuk upvote, -1 untuk downvote
            $table->timestamps();
            $table->unique(['user_id', 'story_id']); //
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};