<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view(name_blade('Backend.dashboard.app'));
    }

    public function config()
    {
        return view(name_blade('Backend.dashboard.config'));
    }

    public function clear_cache()
    {
        Artisan::call('cache:clear');
        Artisan::call('route:cache');
        Artisan::call('route:clear');
        Artisan::call('config:cache');

        return 'Clear All cleared';
    }
}
