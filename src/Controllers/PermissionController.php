<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class PermissionController extends Controller
{
    private $table = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = Roles::TableName();
    }

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

        $roles = Roles::where('status', '1')
            ->orderBy('ordering', 'asc')
            ->get();

        return view(
            name_blade('Backend.permission.app'),
            [
                'menus'      => [
                    'menu-left' => Ucases::EditMenu('left', true),
                    'menu-top'  => Ucases::EditMenu('top', true),
                ],
                'roles'      => $roles,
                'permission' => $permission
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     *
     * @return JsonResponse
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
            'id' => [ 'required', 'numeric', 'unique:' . $this->table . ',id,' . $id ],
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

            Artisan::call('cache:clear');

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
