<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for 'lang' in query parameters
        if ($request->has('lang')) {
            $locale = $request->get('lang');
        }
        // Check the 'Accept-Language' header
        elseif ($request->hasHeader('Accept-Language')) {
            $locale = $request->header('Accept-Language');
            // You may extract only the first language (e.g., "en" from "en-US,en;q=0.9")
            $locale = substr($locale, 0, 2);
        }
        // If no language is found, use the default from config
        else {
            $locale = Config::get('app.locale');
        }

        // Ensure that the locale is supported by your app
        if (in_array($locale, Config::get('app.supported_locales'))) {
            App::setLocale($locale);
        }
        return $next($request);
    }
}
