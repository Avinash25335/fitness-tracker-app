<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkoutPlanController;
use App\Http\Controllers\DietPlanController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TrainerSessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminWorkoutController;
use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    $featuredWorkouts = \App\Models\WorkoutPlan::take(3)->get();
    return view('home', compact('featuredWorkouts'));
})->name('home');

Route::get('/privacy', function () { return view('home'); })->name('privacy');
Route::get('/terms', function () { return view('home'); })->name('terms');
Route::get('/contact', function () { return view('home'); })->name('contact');

// 🔑 Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔑 Password Reset
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// 🔐 Email Verification
Route::get('/email/verify', [AuthController::class, 'showVerifyEmail'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// 🔐 Authenticated Routes
// --- 📤 Export System Routes ---
Route::middleware(['auth'])->group(function () {
    Route::get('/export/progress', [App\Http\Controllers\ExportController::class, 'progress'])->name('export.progress');
    Route::get('/export/workouts', [App\Http\Controllers\ExportController::class, 'workouts'])->name('export.workouts');
    Route::get('/export/diet', [App\Http\Controllers\ExportController::class, 'diet'])->name('export.diet');
    Route::get('/export/invoice/{id}', [App\Http\Controllers\ExportController::class, 'invoice'])->name('export.invoice');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/trainer/dashboard', [\App\Http\Controllers\TrainerDashboardController::class, 'index'])->name('trainer.dashboard');
    Route::put('/trainer/cost', [\App\Http\Controllers\TrainerDashboardController::class, 'updateCost'])->name('trainer.cost.update');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Workouts (Action Routes only)
    Route::post('/workouts/{workout}/start', [WorkoutPlanController::class, 'start'])->name('workouts.start');

    // Nutrition Hub (Action Routes only)
    Route::post('/diets/{diet}/follow', [DietPlanController::class, 'follow'])->name('diets.follow');
    Route::get('/diets/{diet}/download', [DietPlanController::class, 'download'])->name('diets.download');
    Route::post('/nutrition/log', [\App\Http\Controllers\CalorieLogController::class, 'store'])->name('nutrition.log');

    // Transformation / Progress
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::post('/progress', [ProgressController::class, 'store'])->name('progress.store');
    Route::post('/progress/goal', [ProgressController::class, 'updateGoal'])->name('progress.goal.update');
    
    // 🏋️ Trainer Booking System (Conflict-Proof)
    Route::post('/book-session', [TrainerSessionController::class, 'book'])->name('trainer.book');
    Route::post('/reschedule-session/{id}', [TrainerSessionController::class, 'reschedule'])->name('trainer.reschedule');
    Route::get('/booked-slots/{trainer}/{date}', [TrainerSessionController::class, 'getBookedSlots'])->name('trainer.slots');
    Route::post('/cancel-session/{id}', [TrainerSessionController::class, 'cancel'])->name('trainer.cancel');
    Route::get('/my-sessions', [TrainerSessionController::class, 'myBookings'])->name('trainer.my-bookings');

    // API Internal Endpoints
    Route::prefix('api')->group(function () {
        // Workout Tracking & Progress
        Route::post('/plan/start/{planId}', [\App\Http\Controllers\Api\WorkoutController::class, 'startPlan']);
        Route::get('/progress/{planId}', [\App\Http\Controllers\Api\WorkoutController::class, 'getProgress']);
        Route::post('/exercise/complete', [\App\Http\Controllers\Api\WorkoutController::class, 'completeExercise']);
        Route::post('/session/complete/{sessionId}', [\App\Http\Controllers\Api\WorkoutController::class, 'completeSession']);
        Route::post('/session/pause/{sessionId}', [\App\Http\Controllers\Api\WorkoutController::class, 'pauseSession']);
        Route::post('/session/resume/{sessionId}', [\App\Http\Controllers\Api\WorkoutController::class, 'resumeSession']);
        Route::get('/adaptive-plan', [\App\Http\Controllers\Api\WorkoutController::class, 'adaptivePlan']);
        Route::get('/recommendation', [\App\Http\Controllers\Api\WorkoutController::class, 'getRecommendation']);
        Route::get('/achievements', [\App\Http\Controllers\Api\WorkoutController::class, 'checkAchievements']);
        Route::get('/weekly-stats', [\App\Http\Controllers\Api\WorkoutController::class, 'weeklyStats']);

        Route::get('/user-stats', [DashboardController::class, 'getStats']);
        Route::get('/my-bookings', [\App\Http\Controllers\BookingController::class, 'index']);
        Route::post('/book-trainer', [\App\Http\Controllers\BookingController::class, 'book']);
        Route::post('/cancel-booking/{id}', [\App\Http\Controllers\BookingController::class, 'cancel']);
        Route::get('/available-slots/{trainer}', [\App\Http\Controllers\BookingController::class, 'availableSlots']);
        Route::get('/diet', [\App\Http\Controllers\DietPlanController::class, 'getDiet']);
        Route::post('/generate-diet', [\App\Http\Controllers\DietPlanController::class, 'generateDiet']);
        Route::get('/notifications/latest', [\App\Http\Controllers\NotificationController::class, 'getLatest']);
    });
});

// 🌐 Publicly Accessible Content Routes (View Only)
Route::get('/workouts', [WorkoutPlanController::class, 'index'])->name('workouts.index');
Route::get('/workouts/{workout}', [WorkoutPlanController::class, 'show'])->name('workouts.show');
Route::get('/diets', [DietPlanController::class, 'index'])->name('diets.index');
Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers.index');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
// Create must be before {post:slug} to avoid 'create' being matched as a slug
Route::middleware(['auth'])->group(function () {
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::post('/blog/{post}/like', [BlogController::class, 'like'])->name('blog.like');
    Route::post('/blog/{post}/comments', [BlogController::class, 'comment'])->name('blog.comment');
    Route::delete('/blog/comments/{comment}', [BlogController::class, 'deleteComment'])->name('blog.comment.delete');
});
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

// 👑 Admin Control Panel
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    Route::resource('workouts', AdminWorkoutController::class);
    Route::resource('blog', AdminBlogController::class);
});
