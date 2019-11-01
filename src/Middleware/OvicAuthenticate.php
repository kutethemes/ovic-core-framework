<?php

namespace Ovic\Framework;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class OvicAuthenticate extends Middleware
{
	public function handle( $request, Closure $next, ...$guards )
	{
		//
	}
}
