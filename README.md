# PHP_Laravel12_Cashier_Paddle

## Introduction

PHP_Laravel12_Cashier_Paddle is a modern Laravel 12-based SaaS starter project that demonstrates how to integrate the Paddle Payment Gateway using Laravel Cashier Paddle.

This project implements subscription billing using Paddle.js (client-side checkout), allowing users to securely purchase and manage subscriptions without handling sensitive payment data on the server.

It provides a clean, responsive UI built with Tailwind CSS and follows best practices for building scalable subscription-based applications.

---

## Project Overview

This application is designed as a subscription-based SaaS template with the following architecture:

1) Backend

- Built with Laravel 12
- Uses Laravel Cashier Paddle for subscription management
- Handles authentication using Laravel Breeze
- Manages users, subscriptions, and billing records in the database

2) Frontend

- Blade templates with Tailwind CSS
- Paddle.js integrated for client-side checkout
- Responsive and modern UI for subscription page

3) Payment Integration

- Uses Paddle Sandbox environment
- Product and pricing managed via Paddle Dashboard
- Secure checkout via Paddle.js popup
- No sensitive card data handled by the server

4) Local Development

- Uses ngrok to expose local Laravel app over HTTPS
- Required for Paddle domain approval and testing
- Enables full end-to-end payment testing in sandbox mode

---

## Key Features

- Secure subscription payments via Paddle
- Client-side checkout using Paddle.js
- User authentication (Laravel Breeze)
- Subscription management with Cashier
- ngrok integration for local HTTPS testing
- Sandbox payment testing environment
- Clean SaaS-style UI

---

## Requirements

* PHP 8.2+
* Composer
* Laravel 12
* Node.js (optional for UI enhancements)
* Paddle Account

---

## Installation

## Step 1: Create Project

```bash
composer create-project laravel/laravel PHP_Laravel12_Cashier_Paddle "12.*"
cd PHP_Laravel12_Cashier_Paddle
```

---

## Step 2: Install Cashier Paddle

```bash
composer require laravel/cashier-paddle
```

---

## Step 3: Setup Environment

Update `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cashier_paddle_db
DB_USERNAME=root
DB_PASSWORD=


PADDLE_API_KEY=pdl_sdbx_apikey_xxxxx
PADDLE_CLIENT_SIDE_TOKEN=test_xxxxx
PADDLE_ENV=sandbox
```

Run Migration Command:

```bash
php artisan migrate
```

---

## Step 4: Create Product & Price in Paddle

1) Go to Paddle Sandbox Dashboard

- https://sandbox-login.paddle.com/signup

2) Sign up / log in to your sandbox account

3) After login, navigate to:

```
Catalog → Products
```

4) Click Create Product

- Enter product name (e.g. Premium Plan)
- Save the product

5) Create a Price under the product:

- Billing type: Recurring
- Interval: Monthly (or yearly)
- Set price (e.g. ₹200 / $3)
- Save

6) Copy the generated Price ID

Example:

```
pri_01xxxxxxxxxxxxxxxx
```
This Price ID will be used in your Laravel Paddle checkout.


### Setup Paddle Client-Side Token

1) Go to Paddle Dashboard

2) Navigate to:

```
Developer Tools → Authentication
```

3) Click Create Client-Side Token

4) Give it a name (e.g. Laravel App)

5) Copy the generated token

Example:

```
test_xxxxxxxxxxxxxxxxx
```

6) Add the token to your .env file:

```
PADDLE_CLIENT_SIDE_TOKEN=test_xxxxxxxxxxxxxxxxx
```

7) Use this token in your frontend (Blade / JS):

```
Paddle.Initialize({
    token: "{{ env('PADDLE_CLIENT_SIDE_TOKEN') }}"
});
```

---

## Step 5: Install Breeze & ngrok

```bash
composer require laravel/breeze --dev
php artisan breeze:install
npm install 
npm run build
npm install -g ngrok
```

---

## Step 6: Run Migrations

```bash
php artisan vendor:publish --tag=cashier-migrations
php artisan migrate
```

Tables created:

* customers
* subscriptions
* subscription_items
* transactions

---

## Step 7: Update User Model

File: `app/Models/User.php`

```php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Paddle\Billable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

---

## Step 8: Create Controller

```bash
php artisan make:controller SubscriptionController
```

---

## Step 9: Controller Code

File: `app/Http/Controllers/SubscriptionController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('subscription');
    }


    public function checkout(Request $request)
    {
        // No redirect, no return URL
    }
}
```

---

## Step 10: Routes Setup

File: `routes/web.php`

```php
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Subscription Routes (ADD THIS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/subscription', [SubscriptionController::class, 'index'])
        ->name('subscription');

    Route::post('/checkout', [SubscriptionController::class, 'checkout'])
        ->name('checkout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

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
require __DIR__.'/auth.php';
```

---

## Step 11: Subscription View (Frontend Checkout)

File: `resources/views/subscription.blade.php`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Paddle -->
    <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>

    <script>
        Paddle.Environment.set('sandbox');

        Paddle.Initialize({
            token: "{{ env('PADDLE_CLIENT_SIDE_TOKEN') }}"
        });

        function openCheckout() {
            Paddle.Checkout.open({
                items: [
                    {
                        priceId: "pri_xxxxxxxxxxxxxx",
                        quantity: 1
                    }
                ]
            });
        }
    </script>
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100 min-h-screen flex items-center justify-center">

    <!-- Card -->
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-md w-full text-center">

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            🚀 Go Premium
        </h1>

        <p class="text-gray-500 mb-6">
            Unlock all features and enjoy a seamless experience
        </p>

        <!-- Pricing -->
        <div class="mb-6">
            <span class="text-4xl font-extrabold text-indigo-600">₹200</span>
            <span class="text-gray-500">/month</span>
        </div>

        <!-- Features -->
        <ul class="text-left space-y-3 mb-6">
            <li class="flex items-center gap-2 text-gray-700">
                ✅ Unlimited access
            </li>
            <li class="flex items-center gap-2 text-gray-700">
                ✅ Priority support
            </li>
            <li class="flex items-center gap-2 text-gray-700">
                ✅ Premium features
            </li>
        </ul>

        <!-- Button -->
        <button onclick="openCheckout()"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition duration-300 shadow-lg">
            Subscribe Now
        </button>

        <!-- Footer -->
        <p class="text-xs text-gray-400 mt-4">
            Secure payment powered by Paddle
        </p>

    </div>

</body>
</html>
```

- Replace this with the actual Price ID from Paddle dashboard.

---

## Step 12: Dashboard View
 
File: `resources/views/dashboard.blade.php`

```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{ __("You're logged in!") }}

                    <br><br>

                    <!-- Add this -->
                    <a href="{{ route('subscription') }}"
                       style="color: blue; text-decoration: underline;">
                        Go to Subscription
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

---

## Step 13: Setup ngrok for Local Testing

Since Paddle requires a public HTTPS domain, use ngrok to expose your local Laravel application.

### Step 13.1: Create ngrok Account & Get Authtoken

- Go to: https://dashboard.ngrok.com/signup  
- Sign up using Google or Email  
- Get your authtoken from: https://dashboard.ngrok.com/get-started/your-authtoken  

Run this command:

```
ngrok config add-authtoken YOUR_AUTHTOKEN
```

Output should confirm:

```
Authtoken saved to configuration file
```
---

### Step 13.2: Start Laravel Server

php artisan serve

Default URL:

```bash
http://127.0.0.1:8000
```

---

### Step 13.3: Start ngrok Tunnel

```bash
ngrok http 8000
```

You will get a public URL like:

```bash
https://your-random-name.ngrok-free.dev
```

---

### Step 13.4: Domain Approval (Paddle Dashboard)

Add your ngrok domain in Paddle Dashboard:

```bash
your-random-name.ngrok-free.dev
```
Status:

Approved 

---

### Step 13.5: Set Default Payment Link

Set your Paddle checkout / app URL to:

```bash
https://your-random-name.ngrok-free.dev
```

---

### Step 13.6: Open Application via ngrok URL

Access your app using:

```bash
https://your-random-name.ngrok-free.dev/subscription
```

---

### Important Notes

- Do NOT use localhost or partial ngrok URLs  
- Always use the full ngrok domain approved in Paddle  
- If ngrok restarts, the URL will change  
  → You must update and re-approve the new domain in Paddle  
---

##  Testing (Sandbox Mode)

Use test card:

```
Card: 4111 1111 1111 1111
Expiry: Any future date
CVV: 123
```

---

## Output

<img src="screenshots/Screenshot 2026-03-25 155357.png" width="1000">

<img src="screenshots/Screenshot 2026-03-25 182113.png" width="1000">

<img src="screenshots/Screenshot 2026-03-25 170249.png" width="1000">

<img src="screenshots/Screenshot 2026-03-25 170358.png" width="1000">

<img src="screenshots/Screenshot 2026-03-25 170435.png" width="1000">

<img src="screenshots/Screenshot 2026-03-25 170450.png" width="1000">

---

## Project Structure

```
PHP_Laravel12_Cashier_Paddle/
│
├── app/
│   ├── Http/Controllers/SubscriptionController.php
│   └── Models/User.php
│
├── resources/views/
│   └── subscription.blade.php
│
├── routes/web.php
├── config/
├── database/
├── .env
└── README.md
```

---

Your PHP_Laravel12_Cashier_Paddle Project is now ready!

