<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Composer;
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

    public function update_core( Request $request )
    {
        shell_exec('composer clear-cache');
        shell_exec('composer update ovic-core/framework');

        if ( $request->ajax() ) {
            return response()->json([
                'message' => 'Core đã được cập nhật thành công.',
            ]);
        }
        return 'Core đã được cập nhật thành công.';
    }

    public function update_assets( Request $request )
    {
        Artisan::call('vendor:publish --tag=ovic-assets --force');

        if ( $request->ajax() ) {
            return response()->json([
                'message' => 'Thư viện đã được cập nhật thành công.',
            ]);
        }
        return 'Thư viện đã được cập nhật thành công.';
    }

    public function clear_cache( Request $request )
    {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('event:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');

        if ( $request->ajax() ) {
            return response()->json([
                'message' => 'Xóa cache thành công.',
            ]);
        }
        return 'Xóa cache thành công.';
    }
}
