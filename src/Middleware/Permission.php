<?php

namespace Ovic\Framework;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Permission extends Middleware
{
    public function handle( $request, Closure $next, ...$guards )
    {
        return $next($request);
    }
}
