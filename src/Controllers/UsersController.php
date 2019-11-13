<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    private $rules = [
        'name'        => [ 'required', 'string', 'max:100' ],
        'email'       => [ 'required', 'string', 'email', 'max:100', 'unique:users,email' ],
        'password'    => [ 'required', 'string', 'min:8', 'confirmed' ],
        'donvi_id'    => [ 'numeric' ],
        'status'      => [ 'numeric', 'min:0', 'max:2' ],
        'donvi_ids.*' => [ 'string', 'integer' ],
        'role_ids.*'  => [ 'string', 'integer' ],
    ];

    private $messages = [
        'name.required'     => 'Tên hiển thị là trường bắt buộc tối đa 100 kí tự',
        'email.required'    => 'Email là trường bắt buộc tối đa 100 kí tự',
        'email.email'       => 'Email không đúng định dạng',
        'password.required' => 'Mật khẩu là trường bắt buộc',
        'password.min'      => 'Mật khẩu phải chứa ít nhất 8 ký tự',
        'status.max'        => 'Trạng thái chấp nhận 3 ký tự số 0, 1 và 2',
    ];

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
    public function index( Request $request )
    {
        $permission = user_can('all');

        if ( array_sum($permission) == 0 ) {
            abort(404);
        }

        $roles  = [];
        $donvis = [];
        $ucases = [];
        if ( Donvi::hasTable() ) {
            $donvis = Donvi::all([ 'id', 'tendonvi' ]);
        }
        if ( Roles::hasTable() ) {
            $roles = Roles::all([ 'id', 'title' ]);
        }

        return view(
            name_blade('Backend.users.app'),
            compact(
                [ 'donvis', 'roles', 'permission' ]
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        $args = [
            [ 'id', '>', 0 ],
        ];

        $totalData = Users::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $search = $request->input('search.value');

        /* sorting */
        $sort   = $request->input('sorting');
        $status = $request->input('columns.1.search.value');

        if ( $status != '' ) {
            if ( $status == 1 ) {
                $args = [
                    [ 'status', '>', 0 ],
                    [ 'status', '<>', 2 ],
                ];
            } else {
                $args = [
                    [ 'status', '=', $status ],
                ];
            }
        } elseif ( $sort != '' && !empty($search) ) {
            $args = [
                [ 'status', '=', $sort ],
            ];
        }

        if ( empty($search) ) {
            $users = Users::where($args)
                ->offset($start)
                ->limit($limit)
                ->latest()
                ->get()
                ->toArray();
        } else {
            $users = Users::where($args)
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

            $totalFiltered = count($users);
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
     * @param  array  $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
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
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ( $validator->passes() ) {
            $data = $request->toArray();

            $user = new User();

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
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $user = \Auth::user();

        return view(name_blade('Backend.users.show'), compact('user'));
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
                'message' => [ 'Bạn không được cấp quyền sửa dữ liệu.' ],
                'data'    => [],
            ]);
        }
        $dataTable            = [];
        $this->rules['email'] = '';
        if ( !$request->has('password') ) {
            $this->rules['password'] = '';
        }
        if ( !$request->has('name') ) {
            $this->rules['name'] = '';
        }
        if ( $request->has('email') ) {
            $this->rules['email'] = [ 'required', 'string', 'email', 'max:100', 'unique:users,email,'.$id ];
        }
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
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
            if ( !empty($data['role_ids']) ) {
                $data['role_ids'] = maybe_serialize($data['role_ids']);
            }
            if ( !empty($data['donvi_ids']) ) {
                $data['donvi_ids'] = maybe_serialize($data['donvi_ids']);
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
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        $response = [
            'status'  => 'success',
            'title'   => 'Đã xóa!',
            'message' => 'Xóa người dùng thành công.',
        ];
        if ( !user_can('delete') ) {
            $response['status']  = 'warning';
            $response['title']   = 'Bạn không được cấp quyền xóa dữ liệu!';
            $response['message'] = '';

            return response()->json($response);
        }

        if ( !is_numeric($id) ) {
            $deleted = [];
            $ids     = explode(',', $id);

            foreach ( $ids as $id ) {
                $delete = Users::find($id);
                if ( !empty($delete) && $delete->status != 3 ) {
                    $delete->delete();
                    $deleted[] = $id;
                }
            }

            if ( !empty($deleted) ) {
                return response()->json($response);
            }

            $response['status']  = 'error';
            $response['title']   = 'Lỗi!';
            $response['message'] = 'Xóa người dùng không thành công.';

            return response()->json($response);
        }

        if ( Users::find($id)->status == 3 ) {
            $response['status']  = 'warning';
            $response['title']   = 'Bạn không thể xóa người dùng này!';
            $response['message'] = '';

            return response()->json($response);
        }

        $delete = Users::find($id);

        if ( !empty($delete) ) {

            $delete->delete();

            return response()->json($response);
        }

        $response['status']  = 'error';
        $response['title']   = 'Lỗi!';
        $response['message'] = 'Xóa người dùng không thành công.';

        return response()->json($response);
    }
}
