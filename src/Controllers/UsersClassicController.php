<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class UsersClassicController extends Controller
{
    private $table = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = Users::TableName();
    }

    public function rules( $id = null, $request = null )
    {
        $rules = [
            'name'        => [ 'required', 'string', 'max:100' ],
            'email'       => [ 'required', 'string', 'email', 'max:100', 'unique:' . $this->table . ',email' ],
            'password'    => [ 'required', 'string', 'min:8', 'confirmed' ],
            'donvi_id'    => [ 'numeric' ],
            'status'      => [ 'numeric', 'min:0', 'max:2' ],
            'donvi_ids.*' => [ 'string', 'integer' ],
            'role_ids.*'  => [ 'string', 'integer' ],
        ];

        if ( $id != null ) {
            $rules['email'] = [ 'required', 'string', 'email', 'max:100', 'unique:' . $this->table . ',email,' . $id ];
            if ( !$request->has('password') ) {
                $rules['password'] = '';
            }
            if ( !$request->has('name') ) {
                $rules['name'] = '';
            }
            if ( !$request->has('email') ) {
                $rules['email'] = '';
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'     => 'Tên hiển thị là trường bắt buộc tối đa 100 kí tự',
            'email.required'    => 'Email là trường bắt buộc tối đa 100 kí tự',
            'email.email'       => 'Email không đúng định dạng',
            'password.required' => 'Mật khẩu là trường bắt buộc',
            'password.min'      => 'Mật khẩu phải chứa ít nhất 8 ký tự',
            'status.max'        => 'Trạng thái chấp nhận 3 ký tự số 0, 1 và 2',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Factory|View
     */
    public function index( Request $request )
    {
        $permission = user_can('all');

        if ( array_sum($permission) == 0 || !user_can('view') ) {
            abort(404);
        }

        $roles  = [];
        $donvis = [];
        if ( Donvi::hasTable() ) {
            $donvis = Donvi::getDonvi();
        }
        if ( Roles::hasTable() ) {
            $roles = Roles::getRoles();
        }

        return view(
            name_blade('Backend.users-classic.app2'),
            [
                'donvis'     => $donvis,
                'roles'      => $roles,
                'permission' => $permission
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function create( Request $request )
    {
        if ( $request->has('selected') ) {
            $id     = $request->input('selected');
            $donvis = Donvi::where(
                [
                    [ 'status', 1 ],
                    [ 'parent_id', $id ]
                ])
                ->with([
                    'children' => function ( $query ) {
                        $query->where(
                            [
                                [ 'status', 1 ],
                            ]
                        );
                    }
                ])
                ->get()
                ->toArray();

            return response()->json(
                array_keys(remove_level($donvis))
            );
        }
        $user = Auth::user();
        $args = [
            [ 'id', '>', 0 ],
        ];
        if ( $user->status !== 3 ) {
            $args['donvi_id'] = [ 'donvi_id', '=', $user->donvi_id ];
        }

        $totalData = Users::count();
        $limit     = $request->input('length');
        $start     = $request->input('start');
        $search    = $request->input('search.value');

        /* sorting */
        $sort   = $request->input('sorting');
        $status = $request->input('columns.1.search.value');

        if ( $status != '' ) {
            if ( $status == 1 ) {
                $args[] = [ 'status', '>', 0 ];
                $args[] = [ 'status', '<>', 2 ];
            } else {
                $args[] = [ 'status', '=', $status ];
            }
        } elseif ( $sort != '' && !empty($search) ) {
            $args[] = [ 'status', '=', $sort ];
        }

        /* filter */
        $filter = $request->input('filter');

        if ( !empty($filter['donvi_id']) ) {
            $args['donvi_id'] = [ 'donvi_id', '=', $filter['donvi_id'] ];
        }

        $args = array_values($args);

        if ( empty($search) ) {
            $totalFiltered = count(
                Users::where($args)->get()
            );
            $users         = Users::where($args)
                ->offset($start)
                ->limit($limit)
                ->latest()
                ->get()
                ->toArray();
        } else {
            $totalFiltered = count(
                Users::where($args)
                    ->where(
                        function ( $query ) use ( $search ) {
                            $query->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                        }
                    )
                    ->get()
            );
            $users         = Users::where($args)
                ->where(
                    function ( $query ) use ( $search ) {
                        $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    }
                )
                ->offset($start)
                ->limit($limit)
                ->latest()
                ->get()
                ->toArray();
        }

        $data = [];

        if ( !empty($users) ) {
            foreach ( $users as $user ) {
                $data[] = $this->user_data($user);
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

    public function user_data( $user )
    {
        $avatar_url = "img/a_none.jpg";
        $donvi      = "Bảng đơn vị không tồn tại";

        if ( !empty($user['avatar']) && $user['avatar'] > 0 ) {
            $avatar_url = get_attachment_url($user['avatar']);
        } else {
            $user['avatar'] = 0;
        }

        if ( Donvi::hasTable() ) {
            $donvi = Donvi::where('id', $user['donvi_id'])->value('tendonvi');
        }

        $user['avatar_url'] = $avatar_url;
        $user['donvi_text'] = $donvi;
        $user['status']     = $user['status'] < 0 ? 0 : $user['status'];

        return $user;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store( Request $request )
    {
        if ( !user_can('add') ) {
            return response()->json([
                'status'  => 400,
                'message' => [ 'Bạn không được cấp quyền thêm dữ liệu' ],
                'data'    => [],
            ]);
        }

        $dataTable = [];
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ( $validator->passes() ) {
            $data = $request->toArray();

            $user = new Users();

            $user->name      = $data['name'];
            $user->email     = $data['email'];
            $user->avatar    = $data['avatar'];
            $user->password  = Hash::make($data['password']);
            $user->donvi_id  = !empty($data['donvi_id']) ? $data['donvi_id'] : 0;
            $user->donvi_ids = !empty($data['donvi_ids']) ? $data['donvi_ids'] : 0;
            $user->role_ids  = !empty($data['role_ids']) ? $data['role_ids'] : 0;
            $user->status    = $data['status'];

            $user->save();

            $user_id = $user->getAttributeValue('id');

            if ( $request->has('dataTable') ) {
                $user = Users::where('id', $user_id)->get()->first();

                if ( !empty($user) ) {
                    $dataTable = $this->user_data($user);
                }
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Tạo người dùng thành công.',
                'data'    => $dataTable,
            ]);
        }

        return response()->json([
            'status'  => 400,
            'message' => $validator->errors()->all(),
            'data'    => $dataTable,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Factory|View
     */
    public function show( $id )
    {
        $user = \Auth::user();

        return view(name_blade('Backend.users-classic.show'), compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
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
                'message' => [ 'Bạn không được cấp quyền sửa dữ liệu.' ],
                'data'    => [],
            ]);
        }
        $dataTable = [];
        $validator = Validator::make($request->all(), $this->rules($id, $request), $this->messages());
        $data      = $request->except([ '_token', 'id', 'dataTable' ]);

        if ( Users::find($id)->status == 3 ) {
            $data['status']    = 3;
            $data['role_ids']  = 0;
            $data['donvi_id']  = 0;
            $data['donvi_ids'] = 0;
            if ( Auth::user()->status != 3 ) {
                return response()->json([
                    'status'  => 400,
                    'message' => [ 'Bạn không có quyền sửa người dùng này.' ],
                    'data'    => [],
                ]);
            }
        }

        if ( $validator->passes() ) {
            if ( !empty($data['password']) ) {
                $data['password'] = Hash::make($data['password']);
            }
            if ( !empty($data['donvi_id']) ) {
                $data['role_ids'] = !empty($data['role_ids']) ? maybe_serialize($data['role_ids']) : 0;
            }
            if ( !empty($data['donvi_id']) ) {
                $data['donvi_ids'] = !empty($data['donvi_ids']) ? maybe_serialize($data['donvi_ids']) : 0;
            }

            Users::where('id', $id)->update($data);

            if ( $request->has('dataTable') ) {
                $user = Users::where('id', $id)->get()->first();

                if ( !empty($user) ) {
                    $dataTable = $this->user_data($user);
                }
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Update người dùng thành công.',
                'data'    => $dataTable,
            ]);
        }

        return response()->json([
            'status'  => 400,
            'message' => $validator->errors()->all(),
            'data'    => $dataTable,
        ]);
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

        $count = Users::destroy($id);

        if ( $count > 0 ) {
            return response()->json([
                'status'  => 'success',
                'title'   => 'Đã xóa!',
                'message' => 'Đã xóa ' . $count . ' người dùng!',
            ]);
        }
        return response()->json([
            'status'  => 'error',
            'title'   => 'Lỗi!',
            'message' => 'Xóa người dùng không thành công!',
        ]);
    }
}
