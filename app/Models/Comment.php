<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Tambahkan 'parent_id' ke dalam $fillable
    protected $fillable = ['user_id', 'story_id', 'content', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    // Relasi untuk mendapatkan SEMUA balasan dari sebuah komentar
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    // Relasi untuk mendapatkan komentar INDUK (jika komentar ini adalah balasan)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}