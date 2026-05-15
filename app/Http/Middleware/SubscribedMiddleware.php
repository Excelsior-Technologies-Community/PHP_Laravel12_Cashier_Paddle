<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->user()->subscribed()) {

            return redirect()
                ->route('subscription')
                ->with('error', 'Please subscribe to access premium content.');
        }

        return $next($request);
    }
}