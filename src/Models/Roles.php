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

    public function __construct( array $attributes = [] )
    {
        $this->table = config('ovic.table.roles', 'roles');

        parent::__construct($attributes);
    }

    public function scopehasTable( $query )
    {
        if ( Schema::hasTable($this->table) ) {
            return true;
        }

        return false;
    }

    public function scopeTableName( $query )
    {
        return $this->table;
    }

    public function setUcaseIdsAttribute( $value )
    {
        $this->attributes['ucase_ids'] = maybe_serialize($value);
    }

    public function getUcaseIdsAttribute( $value )
    {
        return maybe_unserialize($value);
    }

    public function scopegetRoles( $query )
    {
        $user = Auth::user();

        if ( $user['status'] == 3 ) {
            return Cache::rememberForever(name_cache('roles_supper_admin'),
                function () use ( $query ) {
                    return $query->where('ordering', '>=', 0)
                        ->orderBy('ordering', 'asc')
                        ->get();
                }
            );
        }

        $role_ids = maybe_unserialize($user['role_ids']);

        if ( !empty($role_ids) ) {
            return Cache::rememberForever(name_cache('roles_admin'),
                function () use ( $query, $role_ids ) {
                    return $query->where('ordering', '>', min((array) $role_ids))
                        ->orderBy('ordering', 'asc')
                        ->get();
                }
            );
        }

        return [];
    }

    public function scopePermission( $query, $route = null )
    {
        $permission = [];

        if ( !Auth::check() ) {
            if ( $route != null ) {
                return [ 1, 1, 1, 1 ];
            }
            $ucases = Cache::rememberForever(name_cache('permission'),
                function () use ( $query ) {
                    return Ucases::where('access', 0)
                        ->get('slug')
                        ->toArray();
                }
            );
            if ( !empty($ucases) ) {
                foreach ( $ucases as $ucase ) {
                    $permission[$ucase['slug']] = [ 1, 1, 1, 1 ];
                }
            }
            $permission['dashboard'] = [ 1, 1, 1, 1 ];

            return $permission;
        }

        $user = Auth::user();

        if ( $user['status'] == 3 ) {
            if ( $route != null ) {
                return [ 1, 1, 1, 1 ];
            }
            $ucases = Cache::rememberForever(name_cache('permission_supper_admin'),
                function () use ( $query ) {
                    return Ucases::all('slug')
                        ->toArray();
                }
            );
            if ( !empty($ucases) ) {
                foreach ( $ucases as $ucase ) {
                    $permission[$ucase['slug']] = [ 1, 1, 1, 1 ];
                }
            }
            $permission['ucases']    = [ 1, 1, 1, 1 ];
            $permission['dashboard'] = [ 1, 1, 1, 1 ];

            return $permission;
        }
        if ( !empty($user['role_ids']) ) {

            $roles = Cache::rememberForever(name_cache('permission_admin'),
                function () use ( $query, $user ) {
                    $role_ids = maybe_unserialize($user['role_ids']);

                    return $query->where('status', '1')
                        ->findMany($role_ids, 'ucase_ids');
                }
            );

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
                                        if ( !empty($permission[$key][$index]) && $permission[$key][$index] == 0 && $ucases == 1 ) {
                                            $permission[$key][$index] = $ucases;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $permission['dashboard'] = [ 1, 1, 1, 1 ];
        }

        return $permission;
    }
}
