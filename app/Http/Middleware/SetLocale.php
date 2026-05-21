<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $lang = session('sismart-lang', 'id');
        if (in_array($lang, ['id', 'en'])) {
            app()->setLocale($lang);
        }
        return $next($request);
    }
}
