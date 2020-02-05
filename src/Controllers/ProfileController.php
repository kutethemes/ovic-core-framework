<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

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

        $donvi  = '';
        $diachi = [];
        $canhan = [];
        $user   = Auth::user();
        if ( class_exists(\Modules\Doituong\Entities\Diachi::class) ) {
            $diachi = \Modules\Doituong\Entities\Diachi::where('parent_id', 1)->get();
        }
        if ( !empty($user->canhan_id) && class_exists(\Modules\Doituong\Entities\DTTDCanhan::class) ) {
            $canhan = \Modules\Doituong\Entities\DTTDCanhan::where('id', $user->canhan_id)->get()->first()->toArray();
        }

        if ( empty($canhan) ) {
            $canhan['id']       = 0;
            $canhan['noisinh']  = 0;
            $canhan['gioitinh'] = 1;
            $canhan['scmnd']    = '';
            $canhan['hodem']    = '';
            $canhan['ten']      = '';
            $canhan['ngaysinh'] = today()->format('d/m/Y');
        }

        return view(
            name_blade('Backend.profile.app'),
            [
                'user'       => $user,
                'canhan'     => $canhan,
                'permission' => $permission,
                'diachi'     => $diachi,
                'donvi'      => $donvi,
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

        $user = Auth::user();

        if ( !empty($user->canhan_id) && $user->canhan_id > 0 && class_exists(\Modules\Doituong\Entities\DTTDCanhan::class) ) {
            $validator = Validator::make($request->all(),
                [
                    'hodem'    => 'required|string',
                    'ten'      => 'required|string',
                    'scmnd'    => 'required|numeric',
                    'ngaysinh' => 'required|date_format:d/m/Y',
                    'gioitinh' => 'required|numeric',
                    'noisinh'  => 'required|numeric',//|exists:dm_diadanh',
                ],
                [
                    'hodem.required'    => 'Họ đệm là trường bắt buộc!',
                    'ten.required'      => 'Tên là trường bắt buộc!',
                    'scmnd.required'    => 'Số chứng minh nhân dân là trường bắt buộc!',
                    'scmnd.numeric'     => 'Số chứng minh nhân dân phải là số!',
                    'ngaysinh.required' => 'Ngày sinh là trường bắt buộc!',
                    'ngaysinh.date'     => 'Ngày sinh định dạng ngày chưa đúng!',
                    'gioitinh.required' => 'Giới tính là trường bắt buộc!',
                    'gioitinh.numeric'  => 'Giới tính không hợp lệ!',
                    'noisinh.required'  => 'Nơi sinh là trường bắt buộc!',
                    'noisinh.numeric'   => 'Nơi sinh không hợp lệ!',
                ]
            );

            if ( $validator->passes() ) {
                \Modules\Doituong\Entities\DTTDCanhan::where('id', $user->canhan_id)->update([
                    'hodem'    => $request->input('hodem'),
                    'ten'      => $request->input('ten'),
                    'gioitinh' => $request->input('gioitinh'),
                    'scmnd'    => $request->input('scmnd'),
                    'noisinh'  => $request->input('noisinh'),
                    'ngaysinh' => Carbon::createFromFormat('d/m/Y', $request->input('ngaysinh'))->toDateString(),
                ]);
            }
        }

        if ( $request->has('password') ) {
            $request->request->set('password_confirmation', $request->input('password'));
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
