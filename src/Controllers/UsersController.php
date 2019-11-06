<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    private $rules    = [
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
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles  = [];
        $donvis = [];
        $ucases = [];
        if ( Donvi::hasTable() ) {
            $donvis = Donvi::all([ 'id', 'tendonvi' ])->toArray();
        }
        if ( Roles::hasTable() ) {
            $roles = Roles::all([ 'id', 'title' ])->toArray();
        }
        if ( Ucases::hasTable() ) {
            $ucases = Ucases::all([ 'id', 'title' ])->toArray();
        }

        return view(
            ovic_blade('Backend.users.app'),
            compact(
                [ 'donvis', 'roles', 'ucases' ]
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Show the users list a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function users( Request $request )
    {
        $columns = [
            0 => 'avatar',
            1 => 'name',
            2 => 'donvi_id',
            3 => 'email',
            4 => 'status',
        ];

        $totalData = User::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $sort   = $request->input('sorting');
        $search = $request->input('search.value');
        $status = $request->input('columns.1.search.value');
        /*
        $order = $columns[$request->input( 'order.0.column' )];
        $dir   = $request->input( 'order.0.dir' );
        */

        $sorting = [
            [ 'id', '>', 0 ],
        ];

        if ( $status != '' ) {
            $sorting = [
                [ 'status', '=', $status ],
            ];
        } elseif ( $sort != '' && !empty($search) ) {
            $sorting = [
                [ 'status', '=', $sort ],
            ];
        }

        if ( empty($search) ) {
            $users = User::where($sorting)
                ->offset($start)
                ->limit($limit)
                /* ->orderBy( $order, $dir ) */
                ->latest()
                ->get()
                ->toArray();
        } else {
            $users = User::where($sorting)
                ->where(
                    function ( $query ) use ( $search ) {
                        $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    }
                )
                ->offset($start)
                ->limit($limit)
                /* ->orderBy( $order, $dir ) */
                ->latest()
                ->get()
                ->toArray();

            $totalFiltered = User::where($sorting)
                ->where(
                    function ( $query ) use ( $search ) {
                        $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    }
                )->count();
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
            $path       = Posts::where('id', '=', $user['avatar'])->value('name');
            $avatar_url = route('get_file', explode('/', $path));
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
            $user->donvi_ids = !empty($data['donvi_ids']) ? json_encode($data['donvi_ids']) : 0;
            $user->role_ids  = !empty($data['role_ids']) ? json_encode($data['role_ids']) : 0;
            $user->status    = $data['status'];

            $user->save();

            $user_id = $user->getAttributeValue('id');

            if ( $request->has('dataTable') ) {
                $user = User::where('id', $user_id)->get()->first();

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

        return view(ovic_blade('Backend.users.show'), compact('user'));
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

        if ( $validator->passes() ) {
            if ( !empty($data['password']) ) {
                $data['password'] = Hash::make($data['password']);
            }
            if ( !empty($data['role_ids']) ) {
                $data['role_ids'] = json_encode($data['role_ids'], JSON_NUMERIC_CHECK);
            }
            if ( !empty($data['donvi_ids']) ) {
                $data['donvi_ids'] = json_encode($data['donvi_ids'], JSON_NUMERIC_CHECK);
            }

            User::where('id', $id)->update($data);

            if ( $request->has('dataTable') ) {
                $user = User::where('id', $id)->get()->first();

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
        $delete = User::find($id);

        if ( !empty($delete) ) {
            $delete->delete();

            return response()->json([
                'status'  => 'success',
                'title'   => 'Đã xóa!',
                'message' => 'Xóa người dùng thành công.',
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'title'   => 'Lỗi!',
            'message' => 'Xóa người dùng không thành công.',
        ]);
    }
}
