<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UrlValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $longUrl = $request->input('long_url');
        if (!filter_var($longUrl, FILTER_VALIDATE_URL)) {
            return response()->json([
                'error' => 'The URL provided is not valid'
            ], Response::HTTP_BAD_REQUEST);
        }
        return $next($request);
    }
}
