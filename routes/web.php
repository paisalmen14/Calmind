<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\PsychologistRegisterController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Psychologist\AvailabilityController;
use App\Http\Controllers\Psychologist\ProfileController as PsychologistProfileController;
use App\Http\Controllers\DailyDiaryController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PsychologistController as AdminPsychologistController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;


// Rute ini diubah untuk menampilkan welcome page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard akan menampilkan cerita
Route::get('/dashboard', [StoryController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Pendaftaran Psikolog
Route::get('register/psychologist', [PsychologistRegisterController::class, 'create'])->middleware('guest')->name('psychologist.register');
Route::post('register/psychologist', [PsychologistRegisterController::class, 'store'])->middleware('guest');

Route::middleware('auth')->group(function () {
    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user:username}', [ProfileController::class, 'show'])->name('profile.show');

    // Ruang Cerita Anonim
    Route::resource('stories', StoryController::class)->except(['index', 'show', 'edit', 'update', 'destroy']);
    Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
    Route::get('/stories/{story}', [StoryController::class, 'show'])->name('stories.show');
    Route::get('/story/{story}/edit', [StoryController::class, 'edit'])->name('stories.edit');
    Route::put('/story/{story}', [StoryController::class, 'update'])->name('stories.update');
    Route::delete('/story/{story}', [StoryController::class, 'destroy'])->name('stories.destroy');
    
    Route::post('/stories/{story}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/stories/{story}/vote', [VoteController::class, 'vote'])->name('stories.vote');

    // Rute khusus Psikolog
Route::middleware(['auth', 'role:psikolog'])->prefix('psychologist')->name('psychologist.')->group(function () {
    // TAMBAHKAN ROUTE BARU DI SINI
    Route::get('/dashboard', [App\Http\Controllers\Psychologist\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [PsychologistProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [PsychologistProfileController::class, 'update'])->name('profile.update');
    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [AvailabilityController::class, 'store'])->name('availability.store');
    Route::delete('/availability/{availability}', [AvailabilityController::class, 'destroy'])->name('availability.destroy');
});

    // Artikel
    Route::resource('articles', ArticleController::class)->only(['index', 'show']);

    // Chatbot AI
    Route::get('/konsultasi', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/konsultasi/send', [ChatbotController::class, 'send'])->name('chatbot.send');

    // Chat Konsultasi
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/consultation/{consultation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/consultation/{consultation}', [ChatController::class, 'store'])->name('chat.store');
    
    // Daily Diary
    Route::prefix('daily-diary')->name('daily-diary.')->group(function () {
        Route::get('/', [DailyDiaryController::class, 'index'])->name('index');
        Route::get('/create', [DailyDiaryController::class, 'create'])->name('create');
        Route::post('/', [DailyDiaryController::class, 'store'])->name('store');
        Route::get('/{dailyDiary}', [DailyDiaryController::class, 'show'])->name('show');
        Route::get('/{dailyDiary}/edit', [DailyDiaryController::class, 'edit'])->name('edit');
        Route::put('/{dailyDiary}', [DailyDiaryController::class, 'update'])->name('update');
        Route::delete('/{dailyDiary}', [DailyDiaryController::class, 'destroy'])->name('destroy');
        Route::get('/weekly-summary', [DailyDiaryController::class, 'generateWeeklySummary'])->name('weekly-summary');
    });

    // Rute khusus Psikolog
    Route::middleware(['role:psikolog'])->prefix('psychologist')->name('psychologist.')->group(function () {
        Route::get('/profile', [PsychologistProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [PsychologistProfileController::class, 'update'])->name('profile.update');
        Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
        Route::post('/availability', [AvailabilityController::class, 'store'])->name('availability.store');
        Route::delete('/availability/{availability}', [AvailabilityController::class, 'destroy'])->name('availability.destroy');
    });

    // Rute khusus Pengguna (Pasien)
    Route::middleware(['role:pengguna'])->prefix('consultations')->name('consultations.')->group(function () {
        Route::get('/', [ConsultationController::class, 'index'])->name('index');
        Route::get('/psychologist/{psychologist}', [ConsultationController::class, 'show'])->name('psychologist.show');
        Route::post('/reserve', [ConsultationController::class, 'store'])->name('reserve');
        Route::get('/{consultation}/payment', [PaymentController::class, 'create'])->name('payment.create');
        Route::post('/{consultation}/payment', [PaymentController::class, 'store'])->name('payment.store');
    });

    // Riwayat Konsultasi (Bersama untuk User & Psikolog)
    Route::get('/my-consultations', [ConsultationController::class, 'history'])->name('consultations.history');
});

// Rute khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Route untuk dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('articles', AdminArticleController::class);
    
    // Kelola User
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Verifikasi Psikolog
    Route::get('psychologists', [AdminPsychologistController::class, 'index'])->name('psychologists.index');
    Route::patch('psychologists/{psychologist}/approve', [AdminPsychologistController::class, 'approve'])->name('psychologists.approve');
    Route::patch('psychologists/{psychologist}/reject', [AdminPsychologistController::class, 'reject'])->name('psychologists.reject');

    // Verifikasi Pembayaran Konsultasi
    Route::get('/consultation-verifications', [AdminVerificationController::class, 'index'])->name('consultation.verifications.index');
    Route::post('/consultation-verifications/{consultation}/approve', [AdminVerificationController::class, 'approve'])->name('consultation.verifications.approve');
    Route::post('/consultation-verifications/{consultation}/reject', [AdminVerificationController::class, 'reject'])->name('consultation.verifications.reject');
});

// Otentikasi default Breeze
require __DIR__.'/auth.php';
