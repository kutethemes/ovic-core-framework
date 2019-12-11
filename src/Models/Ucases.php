<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class Ucases extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ucases';

    public function __construct( array $attributes = [] )
    {
        $this->table = config('ovic.table.ucases', 'ucases');

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

    public function setRouteAttribute( $value )
    {
        $this->attributes['route'] = maybe_serialize($value);
    }

    public function getRouteAttribute( $value )
    {
        return maybe_unserialize($value);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->with('children');
    }

    function filter_menu( $resource, $permission )
    {
        $data = [];
        foreach ( $resource as $parent ) {
            if ( !empty($permission[$parent['slug']]) && array_sum($permission[$parent['slug']]) != 0 ) {
                if ( !empty($parent['children']) ) {
                    $parent['children'] = $this->filter_menu($parent['children'], $permission);
                }
                $data[] = $parent;
            }
        }

        return $data;
    }

    public function scopeEditMenu( $query, $position, $is_active = false )
    {
        $activeTXT = $is_active ? 'active' : 'inactive';

        return Cache::rememberForever(name_cache("edit_menu_{$position}_{$activeTXT}"),
            function () use ( $query, $position, $is_active ) {
                $args = [
                    [ 'parent_id', '0' ],
                    [ 'position', $position ],
                ];

                if ( $is_active == true ) {
                    $args[] = [ 'status', '=', '1' ];
                    $args[] = [ 'access', '<>', '0' ];
                }

                return $query->where($args)
                    ->orderBy('ordering', 'asc')
                    ->with([
                        'children' => function ( $query ) use ( $args ) {
                            array_shift($args);
                            $query->where($args)->orderBy('ordering', 'asc');
                        }
                    ])
                    ->get()
                    ->toArray();
            }
        );
    }

    public function scopePrimaryMenu( $query, $position )
    {
        $permission = Roles::Permission();

        return Cache::rememberForever(name_cache("primary_menu_{$position}"),
            function () use ( $query, $position, $permission ) {
                $args     = [
                    [ 'parent_id', 0 ],
                    [ 'status', 1 ],
                    [ 'position', $position ],
                ];
                $resource = $query->where($args)
                    ->with([
                        'children' => function ( $query ) use ( $args ) {
                            array_shift($args);
                            $query->where($args)->orderBy('ordering', 'asc');
                        }
                    ])
                    ->orderBy('ordering', 'asc')
                    ->get()
                    ->toArray();

                return $this->filter_menu($resource, $permission);
            }
        );
    }

    public function scopeGetRoute( $query, $access )
    {
        $ucases = Cache::rememberForever(name_cache("get_route_{$access}"),
            function () use ( $query, $access ) {

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

                return $query->where(
                    [
                        [ 'status', '>', '0' ],
                        [ 'access', $access ],
                    ]
                )->get();
            }
        );

        if ( !empty($ucases) ) {
            foreach ( $ucases as $ucase ) {
                if ( !empty($ucase->route['custom_link']) ) {

                    $method = !empty($ucase->route['method']) ? $ucase->route['method'] : 'get';
                    if ( class_exists($ucase->route['custom_link']) ) {
                        Route::match([ $method ], "{$ucase->slug}", $ucase->route['custom_link']);
                    }

                } elseif ( !empty($ucase->route['controller']) ) {

                    $ClassRoute = $ucase->route['controller'];

                    if ( !empty($ucase->route['module']) ) {
                        $ClassRoute = 'Modules\\' . $ucase->route['module'] . '\Http\Controllers\\' . $ClassRoute;
                    }
                    if ( class_exists("$ClassRoute") ) {
                        Route::resource("{$ucase->slug}", "{$ClassRoute}");
                    }
                }
            }
        }
    }

    /**
     * Destroy the models for the given IDs.
     *
     * @param  Collection|int  $id
     * @return int
     */
    public static function destroy( $id )
    {
        // We'll initialize a count here so we will return the total number of deletes
        // for the operation. The developers can then check this number as a boolean
        // type value or get this total count of records deleted for logging, etc.
        $count = 0;

        // We will actually pull the models from the database table and call delete on
        // each of them individually so that their events get fired properly with a
        // correct set of attributes in case the developers wants to check these.
        $key = ( $instance = new static )->getKeyName();

        foreach ( $instance->where($key, $id)->orwhere('parent_id', $id)->get() as $model ) {

            $roles = Roles::where('ucase_ids', 'LIKE', '%' . $model->slug . '%')->get([ 'id', 'ucase_ids' ]);

            if ( !empty($roles) ) {
                foreach ( $roles as $role ) {
                    $ucase_ids = $role['ucase_ids'];
                    if ( isset($ucase_ids[$model->slug]) ) {
                        unset($ucase_ids[$model->slug]);
                    }
                    Roles::where('id', $role['id'])->update([
                        'ucase_ids' => maybe_serialize($ucase_ids)
                    ]);
                }
            }

            if ( $model->delete() ) {
                $count++;
            }
        }

        return $count;
    }
}
