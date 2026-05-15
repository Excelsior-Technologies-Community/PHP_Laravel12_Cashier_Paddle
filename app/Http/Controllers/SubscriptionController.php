<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Subscription Page
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = auth()->user();

        $subscribed = $user->subscribed();

        return view('subscription', compact('subscribed'));
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Subscription
    |--------------------------------------------------------------------------
    */
    public function cancel()
    {
        $user = auth()->user();

        if ($user->subscribed()) {
            $user->subscription()->cancel();
        }

        return redirect()
            ->route('subscription')
            ->with('success', 'Subscription cancelled successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Resume Subscription
    |--------------------------------------------------------------------------
    */
    public function resume()
    {
        $user = auth()->user();

        if ($user->subscription()->onGracePeriod()) {
            $user->subscription()->resume();
        }

        return redirect()
            ->route('subscription')
            ->with('success', 'Subscription resumed successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Premium Page
    |--------------------------------------------------------------------------
    */
    public function premium()
    {
        return view('premium');
    }
}