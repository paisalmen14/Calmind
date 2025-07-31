<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoodAnalysis extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'user_id',
    'prediction',
    'recommendation',
    'confidence_scores',
    'raw_answers',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'confidence_scores' => 'array',
    'raw_answers' => 'array',
  ];

  /**
   * Get the user that owns the analysis result.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
