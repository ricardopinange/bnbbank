<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AuthenticateWithAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->type === 'Admin') {
            return $next($request);
        }

        return response()->json(
            [
                'error' => true, 
                'message' => false,
                'data' => 'Unauthorized',
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }
}
