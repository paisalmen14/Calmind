<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [ //
        'psychologist_id', //
        'start_time', //
        'end_time', //
        'is_booked', //
    ];

    protected $casts = [ //
        'start_time' => 'datetime', //
        'end_time' => 'datetime', //
    ];

    public function psychologist() //
    {
        return $this->belongsTo(User::class, 'psychologist_id'); //
    }
}