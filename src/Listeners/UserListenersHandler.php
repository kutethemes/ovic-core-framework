<?php

namespace Ovic\Framework;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
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
        //
    }

    /**
     * Handle the event logout.
     *
     * @param  object  $event
     * @return void
     */
    public function logout( Logout $event )
    {
        //
    }
}
