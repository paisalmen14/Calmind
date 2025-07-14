<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon; //

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected static function booted() //
    {
        static::deleting(function ($user) {
            $user->consultationsAsUser()->delete(); //
            $user->consultationsAsPsychologist()->delete(); //
            if ($user->role === 'psikolog') { //
                $user->availabilities()->delete(); //
            }
            $user->stories()->delete(); //
            $user->comments()->delete(); //
            $user->votes()->delete(); //
            $user->paymentConfirmations()->delete(); //
            $user->psychologistProfile()->delete(); //
        });
    }

    protected $fillable = [
        'name',
        'username', // Pastikan kolom ini ada di migrasi users Anda
        'email',
        'password',
        'role',
        'psychologist_status',
        'chosen_psychologist_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'membership_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Definisi Relasi
    public function stories() { return $this->hasMany(Story::class); } //
    public function comments() { return $this->hasMany(Comment::class); } //
    public function psychologistProfile() { return $this->hasOne(PsychologistProfile::class); } //
    public function availabilities() { return $this->hasMany(Availability::class, 'psychologist_id'); } //
    public function consultationsAsUser() { return $this->hasMany(Consultation::class, 'user_id'); } //
    public function consultationsAsPsychologist() { return $this->hasMany(Consultation::class, 'psychologist_id'); } //
    public function chats() { return $this->hasMany(Chat::class, 'sender_id'); } //
    public function votes() { return $this->hasMany(Vote::class); } //
    public function paymentConfirmations() { return $this->hasMany(PaymentConfirmation::class); } //
    public function chosenPsychologist() { return $this->belongsTo(User::class, 'chosen_psychologist_id'); } //
    public function dailyDiaries() { return $this->hasMany(DailyDiary::class); } // Relasi baru untuk Daily Diary

    public function isMember(): bool { return $this->membership_expires_at && $this->membership_expires_at->isFuture(); } //
}