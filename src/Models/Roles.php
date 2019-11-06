<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Schema;

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

    public function scopePermission( $query, $user )
    {
        $permission = [];
        $roles      = $query->findMany(json_decode($user->role_ids, true), 'ucase_ids')
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

        return $permission;
    }
}
