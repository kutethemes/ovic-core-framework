<?php

namespace Ovic\Framework;

use App\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Permission extends Middleware
{
    public function handle( $request, Closure $next, ...$guards )
    {
        $permission = [];
        $user       = auth()->user();
        $roles      = Roles::findMany(json_decode($user->role_ids, true), 'ucase_ids')
            ->collect()
            ->each(function ( $item, $key ) {
                $item->ucase_ids = json_decode($item->ucase_ids, true);
            })
            ->toArray();

        if ( !empty($roles) ) {
            foreach ( $roles as $role ) {
                foreach ( $role['ucase_ids'] as $key => $ucases_id ) {
                    if ( !isset($permission[$key]) ) {
                        $permission[$key] = $ucases_id;
                    } else {
                        foreach ( $ucases_id as $index => $ucases ) {
                            if ( $permission[$key][$index] == 0 && $ucases == 1 ) {
                                $permission[$key][$index] = $ucases;
                            }
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
