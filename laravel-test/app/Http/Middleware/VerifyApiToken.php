<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiToken
{
    public function handle(Request $request, Closure $next)
    {
        // Skip authentication for GET requests
        if ($request->isMethod('get')) {
            return $next($request);
        }

        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
