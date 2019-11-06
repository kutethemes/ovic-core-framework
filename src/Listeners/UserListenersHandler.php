<?php

namespace Ovic\Framework;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

use Illuminate\Http\Request;
use App\User;

class UserListenersHandler
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct( Request $request )
    {
        $this->request = $request;
    }

    /**
     * Handle the event login.
     *
     * @param  object  $event
     * @return void
     */
    public function login( Login $event )
    {
        $user = $event->user;
        if ( !$this->request->session()->has('permission') ) {
            $this->request->session()->put('permission', $this->permission($user));
        }
    }

    /**
     * Handle the event logout.
     *
     * @param  object  $event
     * @return void
     */
    public function logout( Logout $event )
    {
        $this->request->session()->forget('permission');
    }

    public function permission( $user )
    {
        $permission = [];
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

        return $permission;
    }
}
