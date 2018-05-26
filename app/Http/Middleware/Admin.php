<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('x-auth-key') != 'Zioj23D92j2kGf9D') {
            return response()->json(['success' => false, 'response' => 'Unauthorized or Invalid Credentials.'], 401);
        }
        return $next($request);
    }
}
