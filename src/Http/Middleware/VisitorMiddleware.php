<?php

namespace Habib\Dashboard\Http\Middleware;

use Closure;
use Habib\Dashboard\Helpers\Helper;
use Illuminate\Http\Request;

class VisitorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        Helper::visitor($request);

        return $next($request);
    }
}
