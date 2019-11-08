<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class UcasesController extends Controller
{
    private $rules    = [
        'slug'      => [ 'required', 'string', 'max:100', 'unique:ucases,slug' ],
        'title'     => [ 'required', 'string', 'max:100' ],
        'ordering'  => [ 'numeric', 'min:0' ],
        'parent_id' => [ 'numeric', 'min:0' ],
        'access'    => [ 'numeric', 'min:0', 'max:2' ],
        'status'    => [ 'numeric', 'min:0', 'max:2' ],
        'route.*'   => [ 'string', 'nullable' ],
    ];
    private $messages = [
        'slug.required'  => 'Tên route là trường bắt buộc tối đa 100 kí tự',
        'title.required' => 'Tên hiển thị là trường bắt buộc tối đa 100 kí tự',
        'access.max'     => 'Quyền truy cập chấp nhận 3 ký tự số 0, 1 và 2',
        'status.max'     => 'Trạng thái chấp nhận 3 ký tự số 0, 1 và 2',
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
        $permission = user_can('all');

        if ( array_sum($permission) == 0 ) {
            abort(404);
        }

        $menus = [
            'menu-left' => Ucases::EditMenu('left'),
            'menu-top'  => Ucases::EditMenu('top'),
        ];

        return view(name_blade('Backend.ucases.app'), compact([ 'menus', 'permission' ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        if ( !user_can('edit') ) {
            return response()->json([
                'status'  => 'warning',
                'message' => 'Bạn không được cấp quyền sửa dữ liệu',
            ]);
        }

        $request  = $request->toArray();
        $position = !empty($request['position']) ? $request['position'] : 'left';

        if ( !empty($request['data']) ) {
            foreach ( $request['data'] as $key => $data ) {
                $update = [
                    'ordering'  => $key,
                    'parent_id' => 0,
                    'position'  => $position
                ];
                Ucases::where('id', $data['id'])
                    ->update($update);

                if ( !empty($data['children']) ) {
                    foreach ( $data['children'] as $key => $children ) {
                        $update = [
                            'ordering'  => $key,
                            'parent_id' => $data['id'],
                            'position'  => $position
                        ];
                        Ucases::where('id', $children['id'])
                            ->update($update);
                    }
                }
            }
        }

        return response()->json(
            [
                'status'  => 'info',
                'message' => 'Cập nhật menu thành công.',
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        if ( !user_can('add') ) {
            return response()->json([
                'status'  => 400,
                'message' => [ 'Bạn không được cấp quyền thêm dữ liệu' ],
            ]);
        }

        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ( $validator->passes() ) {
            $data          = $request->toArray();
            $route         = array_map('htmlentities', $data['route']);
            $data['route'] = html_entity_decode(json_encode($route));
            $ordering      = Ucases::where([
                [ 'parent_id', 0 ],
                [ 'position', $data['position'] ],
            ])->count();

            $ucase = new Ucases();

            $ucase->slug      = $data['slug'];
            $ucase->title     = $data['title'];
            $ucase->ordering  = $ordering;
            $ucase->parent_id = 0;
            $ucase->route     = !empty($data['route']) ? $data['route'] : '';
            $ucase->position  = !empty($data['position']) ? $data['position'] : 'left';
            $ucase->access    = !empty($data['access']) ? $data['access'] : 1;
            $ucase->status    = !empty($data['status']) ? $data['status'] : 1;

            $ucase->save();

            $ucase_id = $ucase->getAttributeValue('id');

            return response()->json([
                'status'  => 200,
                'message' => 'Tạo chức năng thành công.',
                'id'      => $ucase_id,
                'icon'    => $route['icon'],
            ]);
        }

        return response()->json([
            'status'  => 400,
            'message' => $validator->errors()->all(),
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
        //
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
        $edit = Ucases::find($id);

        if ( !empty($edit) ) {
            $edit['route'] = json_decode($edit['route'], true);
            return response()->json(
                [
                    'status' => 'success',
                    'data'   => $edit,
                ]
            );
        }

        return response()->json(
            [
                'status'  => 'error',
                'title'   => 'Lỗi!',
                'message' => 'Không tìm thấy chức năng.',
            ]
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
            ]);
        }

        $this->rules['slug'] = '';
        if ( $request->has('slug') ) {
            $this->rules['slug'] = [ 'required', 'string', 'max:100', 'unique:ucases,slug,'.$id ];
        }
        if ( !$request->has('title') ) {
            $this->rules['title'] = '';
        }
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $data      = $request->except([ '_token', 'id' ]);

        if ( $validator->passes() ) {
            if ( !empty($data['route']) ) {
                $route         = array_map('htmlentities', $data['route']);
                $data['route'] = html_entity_decode(json_encode($route));
            }

            Ucases::where('id', $id)->update($data);

            return response()->json([
                'status'  => 200,
                'message' => 'Update chức năng thành công.',
            ]);
        }

        return response()->json([
            'status'  => 400,
            'message' => $validator->errors()->all(),
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
        if ( !user_can('delete') ) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Bạn không được cấp quyền xóa dữ liệu!',
            ]);
        }

        $delete = Ucases::where('id', $id)->orwhere('parent_id', $id);

        if ( !empty($delete) ) {
            /* Deleted */
            $delete->delete();

            return response()->json(
                [
                    'status'  => 'success',
                    'title'   => 'Đã xóa!',
                    'message' => 'Xóa chức năng thành công.',
                ]
            );
        }

        return response()->json(
            [
                'status'  => 'error',
                'title'   => 'Lỗi!',
                'message' => 'Xóa chức năng không thành công.',
            ]
        );
    }
}
