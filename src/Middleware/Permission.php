<?php

namespace Ovic\Framework;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Permission extends Middleware
{
    public function handle( $request, Closure $next, ...$guards )
    {
        if ( Auth::user()->status == 0 ) {
            abort(404);
        }
        return $next($request);
    }
}
