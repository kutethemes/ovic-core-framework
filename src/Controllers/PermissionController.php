<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permission = user_can('all');

        if ( array_sum($permission) == 0 ) {
            abort(404);
        }

        $roles = Roles::where('status', '1')
            ->orderBy('ordering', 'asc')
            ->get();

        $menus = [
            'menu-left' => Ucases::EditMenu('left', true),
            'menu-top'  => Ucases::EditMenu('top', true),
        ];

        return view(
            name_blade('Backend.permission.app'),
            compact([
                'menus',
                'roles',
                'permission'
            ])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        if ( !user_can('edit') ) {
            return response()->json([
                'status'  => 400,
                'message' => [ 'Bạn không được cấp quyền sửa dữ liệu' ],
                'data'    => [],
            ]);
        }

        $validator = Validator::make($request->all(), [
            'id' => [ 'required', 'numeric', 'unique:roles,id,'.$id ],
        ]);
        $data      = $request->except([ '_token', 'id' ]);

        if ( $validator->passes() ) {
            $ucase_ids = Roles::where('id', $id)
                ->value('ucase_ids');

            if ( !empty($data) ) {
                foreach ( $data as $key => $value ) {
                    if ( is_array($value) ) {
                        if ( array_sum($value) == 0 ) {
                            unset($ucase_ids[$key]);
                        } else {
                            $ucase_ids[$key] = $value;
                        }
                    }
                }
            }

            /* update */
            $roles            = Roles::find($id);
            $roles->ucase_ids = $ucase_ids;
            $roles->save();

            Cache::flush();

            return response()->json([
                'status'  => 200,
                'message' => 'Phân quyền thành công.',
                'data'    => $ucase_ids,
                'count'   => count($ucase_ids),
            ]);
        }

        return response()->json([
            'status'  => 400,
            'message' => $validator->errors()->all(),
        ]);
    }
}
