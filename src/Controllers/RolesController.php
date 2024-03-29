<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class RolesController extends Controller
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

    public function rules( $id = null, $request = null )
    {
        $rules = [
            'name'     => [ 'required', 'string', 'max:100', 'unique:' . $this->table . ',name' ],
            'title'    => [ 'required', 'string', 'max:100' ],
            'ordering' => [ 'numeric', 'min:0' ],
            'status'   => [ 'numeric', 'min:0', 'max:1' ],
        ];
        if ( $id != null ) {
            $rules['name'] = [ 'required', 'string', 'max:100', 'unique:' . $this->table . ',name,' . $id ];
            if ( !$request->has('name') ) {
                $rules['name'] = '';
            }
            if ( !$request->has('title') ) {
                $rules['title'] = '';
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'  => 'Tên là trường bắt buộc tối đa 100 kí tự',
            'title.required' => 'Tên hiển thị là trường bắt buộc tối đa 100 kí tự',
            'ordering.min'   => 'Order nhận giá trị số lớn hơn 0',
            'status.max'     => 'Trạng thái chấp nhận 2 ký tự số 0 và 1',
        ];
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

        return view(
            name_blade('Backend.roles.app'),
            compact(
                [ 'permission' ]
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create( Request $request )
    {
        $args = [
            [ 'id', '>', 0 ],
        ];

        $totalData = Roles::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $search = $request->input('search.value');

        /* sorting */
        $sort   = $request->input('sorting');
        $status = $request->input('columns.1.search.value');

        if ( $status != '' ) {
            $args = [
                [ 'status', '=', $status ],
            ];
        } elseif ( $sort != '' && !empty($search) ) {
            $args = [
                [ 'status', '=', $sort ],
            ];
        }

        if ( empty($search) ) {
            $roles = Roles::where($args)
                ->offset($start)
                ->limit($limit)
                ->orderBy('ordering', 'asc')
                ->get()
                ->toArray();
        } else {
            $roles = Roles::where($args)
                ->where(
                    function ( $query ) use ( $search ) {
                        $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('title', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
                    }
                )
                ->offset($start)
                ->limit($limit)
                ->orderBy('ordering', 'asc')
                ->get()
                ->toArray();

            $totalFiltered = count($roles);
        }

        $data = [];

        if ( !empty($roles) ) {
            foreach ( $roles as $role ) {
                $data[] = $this->role_data($role);
            }
        }

        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
        ];

        return response()->json($json_data);
    }

    public function role_data( $role )
    {
        $role['status'] = $role['status'] < 0 ? 0 : $role['status'];

        return $role;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function store( Request $request )
    {
        if ( !user_can('add') ) {
            return response()->json([
                'status'  => 400,
                'message' => [ 'Bạn không được cấp quyền thêm dữ liệu' ],
            ]);
        }

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ( $validator->passes() ) {
            $data = $request->toArray();

            $role = new Roles();

            $role->name        = $data['name'];
            $role->title       = $data['title'];
            $role->description = $data['description'];
            $role->status      = $data['status'];
            $role->ordering    = $data['ordering'];

            $role->save();

            return response()->json(
                [
                    'status'  => 200,
                    'message' => 'Tạo nhóm thành công.',
                ]
            );
        }

        return response()->json(
            [
                'status'  => 400,
                'message' => $validator->errors()->all(),
            ]
        );
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

        $dataTable = [];
        $validator = Validator::make($request->all(), $this->rules($id, $request), $this->messages());
        $data      = $request->except([ '_token', 'id', 'dataTable' ]);

        if ( $validator->passes() ) {

            if ( !empty($data['ucase_ids']) ) {
                $data['ucase_ids'] = maybe_serialize($data['ucase_ids']);
            }

            Roles::where('id', $id)->update($data);

            if ( $request->has('dataTable') ) {
                $role = Roles::where('id', $id)->get()->first();

                if ( !empty($role) ) {
                    $dataTable = $this->role_data($role);
                }
            }

            Artisan::call('cache:clear');

            return response()->json(
                [
                    'status'  => 200,
                    'message' => 'Update nhóm thành công.',
                    'data'    => $dataTable,
                ]
            );
        }

        return response()->json(
            [
                'status'  => 400,
                'message' => $validator->errors()->all(),
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return JsonResponse
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

        $count = Roles::destroy($id);

        if ( $count > 0 ) {

            Artisan::call('cache:clear');

            return response()->json([
                'status'  => 'success',
                'title'   => 'Đã xóa!',
                'message' => 'Đã xóa ' . $count . ' nhóm người dùng!',
            ]);
        }
        return response()->json([
            'status'  => 'error',
            'title'   => 'Lỗi!',
            'message' => 'Xóa nhóm người dùng không thành công!',
        ]);
    }
}
