<?php

namespace Habib\Dashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $app = app();
        $default_local = $app->getLocale();

        if ($request->is('api/*')) {
            $locale = $request->get('lang', $request->get('local', $default_local));
        } else {
            $locale = $request->header('Accept-Language');
            $locale = explode(',', $locale ?? $default_local)[0] ?? $default_local;
            $locale = explode('-', $locale)[0] ?? $default_local;
            $locale = $request->get('lang',
                $request->get('local',
                    $request->hasSession() ? $request->session()->get('locale', $locale) : $default_local
                )
            );
        }

        if (!in_array($locale, config('app.locales'))) {
            $locale = config('app.fallback_locale');
        }

        if ($locale !== $default_local) {
            $request->setLocale($locale);
            $app->setLocale($locale);
        }

        if (!$request->is('api/*')) {
            $request->session()->put('locale', $locale);
        }

        return $next($request);
    }
}
