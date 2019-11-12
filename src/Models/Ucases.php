<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;

class Ucases extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ucases';

    public function scopehasTable( $query )
    {
        if ( Schema::hasTable($this->table) ) {
            return true;
        }

        return false;
    }

    public function setRouteAttribute( $value )
    {
        $this->attributes['route'] = maybe_serialize($value);
    }

    public function getRouteAttribute( $value )
    {
        return maybe_unserialize($value);
    }

    public function scopeEditMenu( $query, $position, $is_active = false )
    {
        $args = [
            [ 'position', $position ]
        ];

        if ( $is_active == true ) {
            $args[] = [ 'status', '=', '1' ];
            $args[] = [ 'access', '<>', '0' ];
        }

        return $query->where($args)
            ->get()
            ->collect()
            ->sortBy('ordering')
            ->groupBy('parent_id')
            ->toArray();
    }

    public function scopePrimaryMenu( $query, $position )
    {
        $permission = Roles::Permission();

        return $query->where(
            [
                [ 'status', '1' ],
                [ 'position', $position ]
            ])
            ->get()
            ->collect()
            ->filter(function ( $item, $key ) use ( $permission ) {
                return ( !empty($permission[$item->slug]) && array_sum($permission[$item->slug]) != 0 );
            })
            ->sortBy('ordering')
            ->groupBy('parent_id')
            ->toArray();
    }

    public function scopeGetRoute( $query, $access )
    {
        switch ( $access ) {
            case 'backend':
                $access = 1;
                break;
            case 'frontend':
                $access = 2;
                break;
            case 'public':
                $access = 0;
                break;
        }

        $ucases = $query->where(
            [
                [ 'status', '>', '0' ],
                [ 'access', $access ],
            ]
        )->get();

        if ( !empty($ucases) ) {
            foreach ( $ucases as $ucase ) {
                if ( !empty($ucase->route['custom_link']) ) {
                    Route::get($ucase->route['custom_link']);
                } elseif ( !empty($ucase->route['controller']) ) {
                    $module = "";
                    if ( !empty($ucase->route['module']) ) {
                        $module = "{$ucase->route['module']}::";
                    }
                    Route::resource("{$ucase->slug}", "{$module}{$ucase->route['controller']}");
                }
            }
        }
    }
}
