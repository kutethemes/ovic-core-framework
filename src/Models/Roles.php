<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class Roles extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';


    public function scopehasTable( $query )
    {
        if ( Schema::hasTable($this->table) ) {
            return true;
        }

        return false;
    }

    public function scopePermission( $query, $route = null )
    {
        $permission = [];
        $user       = Auth::user();
        if ( $user['status'] == 3 ) {

            if ( $route != null ) {
                return [ 1, 1, 1 ];
            }

            $roles = Ucases::all('slug')->toArray();

            if ( !empty($roles) ) {
                foreach ( $roles as $role ) {
                    $permission[$role['slug']] = [ 1, 1, 1 ];
                }
            }

            $permission['ucases']    = [ 1, 1, 1 ];
            $permission['dashboard'] = [ 1, 1, 1 ];

            return $permission;
        }
        if ( !empty($user['role_ids']) ) {
            $role_ids = !is_array($user['role_ids']) ? json_decode($user['role_ids'], true) : $user['role_ids'];
            $roles    = $query->where('status', '1')
                ->findMany($role_ids, 'ucase_ids')
                ->collect()
                ->each(function ( $item, $key ) {
                    $item->ucase_ids = json_decode($item->ucase_ids, true);
                })
                ->toArray();

            if ( !empty($roles) ) {
                foreach ( $roles as $role ) {
                    if ( !empty($role['ucase_ids']) ) {
                        foreach ( $role['ucase_ids'] as $key => $ucases_id ) {
                            if ( $route != null ) {
                                if ( $key == $route ) {
                                    return $ucases_id;
                                }
                            } else {
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
                }
            }
            $permission['dashboard'] = [ 1, 1, 1 ];
        }

        return $permission;
    }
}
