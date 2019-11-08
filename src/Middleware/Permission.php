<?php

namespace Ovic\Framework;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

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
