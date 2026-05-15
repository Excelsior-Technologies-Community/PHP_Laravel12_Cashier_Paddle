<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Laravel\Paddle\Http\Controllers\WebhookController;

Route::post('/paddle/webhook', WebhookController::class);

/*
|--------------------------------------------------------------------------
| Welcome Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Subscription Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/subscription', [SubscriptionController::class, 'index'])
        ->name('subscription');

    Route::post('/checkout', [SubscriptionController::class, 'checkout'])
        ->name('checkout');

    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])
        ->name('subscription.cancel');

    Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])
        ->name('subscription.resume');

    /*
    |--------------------------------------------------------------------------
    | Premium Protected Route
    |--------------------------------------------------------------------------
    */
    Route::get('/premium', [SubscriptionController::class, 'premium'])
        ->middleware('subscribed')
        ->name('premium');

    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';