<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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
        return view(
            name_blade('Backend.dashboard.app')
        );
    }

    public function configs()
    {
        return view(
            name_blade('Backend.dashboard.configs')
        );
    }

    public function dump_autoload( Request $request )
    {
        shell_exec('composer dump-autoload');

        if ( $request->ajax() ) {
            return response()->json([
                'message' => 'Autoload đã được cập nhật thành công.',
            ]);
        }
        return 'Autoload đã được cập nhật thành công.';
    }

    public function update_modules( Request $request )
    {
        Artisan::call('module:publish');

        if ( $request->ajax() ) {
            return response()->json([
                'message' => 'Modules đã được cập nhật thành công.',
            ]);
        }
        return 'Modules đã được cập nhật thành công.';
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
        Artisan::call('clear-compiled');

        if ( $request->ajax() ) {
            return response()->json([
                'message' => 'Xóa cache thành công.',
            ]);
        }
        return 'Xóa cache thành công.';
    }
}
