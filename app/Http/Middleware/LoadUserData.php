<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemSettings;

class LoadUserData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $systemSettings = SystemSettings::orderByDesc('created_at')->first();

            if ($systemSettings) {
                setSession('settings', $systemSettings);
            }
        }

        return $next($request);
    }
}
