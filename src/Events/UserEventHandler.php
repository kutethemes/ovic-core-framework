<?php

namespace Ovic\Framework;

class UserEventHandler
{
    /**
     * Handle user login events.
     */
    public function UserLogin( $event )
    {
        //
    }

    /**
     * Handle user logout events.
     */
    public function UserLogout( $event )
    {
        //
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe( $events )
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'Ovic\Framework\UserEventHandler@UserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'Ovic\Framework\UserEventHandler@UserLogout'
        );
    }

}
