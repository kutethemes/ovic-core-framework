<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @return Factory|View
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

    public function systems( Request $request, $action )
    {
        if ( method_exists($this, $action) ) {
            return call_user_func(
                [ $this, $action ], $request
            );
        }

        return 'function không tồn tại.';
    }

    public function save_change( Request $request )
    {
        //
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
//        Artisan::call('storage:link');

        if ( $request->ajax() ) {
            return response()->json([
                'message' => 'Xóa cache thành công.',
            ]);
        }
        return 'Xóa cache thành công.';
    }
}
