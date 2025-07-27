<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyDiary extends Model
{
    use HasFactory;

    // Tambahkan 'title' ke dalam array $fillable
    protected $fillable = ['user_id', 'entry_date', 'title', 'content', 'summary'];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}