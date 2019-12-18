<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Doituong\Entities\Diachi;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $permission = user_can('all');

        if ( array_sum($permission) == 0 || !user_can('view') ) {
            abort(404);
        }

        $diachi = [];
        if ( class_exists(Diachi::class) ) {
            $diachi = Diachi::select('id', 'parent_id', 'tendiadanh')->where('parent_id', 1)->get();
        }

        return view(
            name_blade('Backend.profile.app'),
            [
                'user'       => Auth::user(),
                'permission' => $permission,
                'diachi'     => $diachi,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return void
     */
    public function store( Request $request )
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show( $id )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit( $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     *
     * @return string
     */
    public function update( Request $request, $id )
    {
        if ( !user_can('edit') ) {
            return response()->json([
                'status'  => 400,
                'message' => [ 'Bạn không được cấp quyền sửa dữ liệu.' ],
            ]);
        }

        if ( Route::has('users-classic.update') ) {
            return redirect()->route(
                'users-classic.update', $id
            );
        } elseif ( Route::has('users.update') ) {
            return redirect()->route(
                'users.update', $id
            );
        }

        return response()->json([
            'status'  => 400,
            'message' => [ 'Cập nhật người dùng không thành công.' ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return string
     */
    public function destroy( $id )
    {
        if ( !user_can('delete') ) {
            return response()->json([
                'status'  => 'warning',
                'title'   => 'Cảnh báo!',
                'message' => 'Bạn không được cấp quyền xóa dữ liệu!',
            ]);
        }

        if ( Route::has('users-classic.destroy') ) {
            return redirect()->route(
                'users-classic.destroy', $id
            );
        } elseif ( Route::has('users.destroy') ) {
            return redirect()->route(
                'users.destroy', $id
            );
        }

        return response()->json([
            'status'  => 400,
            'message' => [ 'Xóa người dùng không thành công!' ],
        ]);
    }
}
