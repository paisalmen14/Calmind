<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content', 'is_anonymous'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest(); // Tampilkan komentar terbaru dulu
    }
        public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // Accessor untuk menghitung total skor (upvotes - downvotes)
    public function getScoreAttribute()
    {
        return $this->votes()->sum('vote');
    }

    // Helper untuk mengecek vote dari user yang sedang login
    public function userVote()
    {
        if (!auth()->check()) {
            return 0;
        }
        return $this->votes()->where('user_id', auth()->id())->value('vote') ?? 0;
    }
}