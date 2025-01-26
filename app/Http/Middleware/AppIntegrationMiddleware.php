<?php

namespace App\Http\Middleware;

use App\Models\Integration;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class AppIntegrationMiddleware
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
        $header = $request->header('X-App-Secret-Key');
        if (!$header || empty($header)) throw new AuthorizationException('You are not authorized to perform this action.');

        $secret = Integration::where('app_secret_key', $header)->first();
        if (!$secret) throw new AuthorizationException('Unauthorized! Invalid key supplied.');
        
        return $next($request);
    }
}
