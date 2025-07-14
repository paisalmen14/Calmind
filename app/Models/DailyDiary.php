<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyDiary extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'entry_date', 'content', 'summary'];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}