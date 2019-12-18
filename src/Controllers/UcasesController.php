<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class UcasesController extends Controller
{
    private $table = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = Ucases::TableName();
    }

    public function rules( $id = null )
    {
        $rules = [
            'slug'      => [ 'required', 'string', 'max:100', 'unique:' . $this->table . ',slug' ],
            'title'     => [ 'required', 'string', 'max:100' ],
            'ordering'  => [ 'numeric', 'min:0' ],
            'parent_id' => [ 'numeric', 'min:0' ],
            'access'    => [ 'numeric', 'min:0', 'max:2' ],
            'status'    => [ 'numeric', 'min:0', 'max:2' ],
            'route.*'   => [ 'string', 'nullable' ],
        ];
        if ( $id != null ) {
            $rules['slug'] = [ 'required', 'string', 'max:100', 'unique:' . $this->table . ',slug,' . $id ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'slug.required'  => 'Tên route là trường bắt buộc tối đa 100 kí tự',
            'slug.unique'    => 'Tên route đã được sử dụng',
            'title.required' => 'Tên hiển thị là trường bắt buộc tối đa 100 kí tự',
            'access.max'     => 'Quyền truy cập chấp nhận 3 ký tự số 0, 1 và 2',
            'status.max'     => 'Trạng thái chấp nhận 3 ký tự số 0, 1 và 2',
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
            name_blade('Backend.ucases.app'),
            [
                'menus'      => [
                    'menu-left' => Ucases::EditMenu('left'),
                    'menu-top'  => Ucases::EditMenu('top'),
                ],
                'permission' => $permission
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
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
            foreach ( $request['data'] as $order_parent => $parent ) {
                $update = [
                    'ordering'  => $order_parent,
                    'parent_id' => 0,
                    'position'  => $position
                ];
                Ucases::where('id', $parent['id'])->update($update);

                if ( !empty($parent['children']) ) {
                    foreach ( $parent['children'] as $order_child => $children ) {
                        $update = [
                            'ordering'  => $order_child,
                            'parent_id' => $parent['id'],
                            'position'  => $position
                        ];
                        Ucases::where('id', $children['id'])->update($update);
                    }
                }
            }
        }

        Artisan::call('cache:clear');

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
            $data     = $request->toArray();
            $ordering = Ucases::where([
                [ 'parent_id', 0 ],
                [ 'position', $data['position'] ],
            ])->count();

            $ucase = new Ucases();

            $ucase->slug      = Str::slug($data['slug'], '-');
            $ucase->title     = $data['title'];
            $ucase->ordering  = $ordering;
            $ucase->parent_id = 0;
            $ucase->route     = $data['route'];
            $ucase->position  = !empty($data['position']) ? $data['position'] : 'left';
            $ucase->access    = !empty($data['access']) ? $data['access'] : 1;
            $ucase->status    = !empty($data['status']) ? $data['status'] : 1;

            $ucase->save();

            $ucase_id = $ucase->getAttributeValue('id');

            Artisan::call('cache:clear');

            return response()->json([
                'status'  => 200,
                'message' => 'Tạo chức năng thành công.',
                'id'      => $ucase_id,
                'icon'    => $data['route']['icon'],
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
     * @return JsonResponse
     */
    public function edit( $id )
    {
        $edit = Ucases::find($id);

        if ( !empty($edit) ) {
            $edit['_slug'] = $edit['slug'];

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
            ]);
        }

        $validator = Validator::make($request->all(), $this->rules($id), $this->messages());
        $data      = $request->except([ '_token', '_slug', 'id' ]);
        $_slug     = $request->input('_slug');

        if ( $validator->passes() ) {

            if ( !empty($data['slug']) ) {

                $slug = Str::slug($data['slug'], '-');

                if ( $slug != $_slug ) {

                    $roles = Roles::where('ucase_ids', 'LIKE', '%' . $_slug . '%')
                        ->get([ 'id', 'ucase_ids' ]);

                    if ( !empty($roles) ) {
                        foreach ( $roles as $role ) {
                            $ucase_ids = $role['ucase_ids'];
                            if ( !empty($ucase_ids[$_slug]) ) {
                                $ucase_ids[$slug] = $ucase_ids[$_slug];

                                unset($ucase_ids[$_slug]);

                                Roles::where('id', $role['id'])->update([
                                    'ucase_ids' => maybe_serialize($ucase_ids)
                                ]);
                            }
                        }
                    }
                }
            }

            if ( !empty($data['route']) ) {
                $data['route'] = maybe_serialize($data['route']);
            }

            Ucases::where('id', $id)->update($data);

            Artisan::call('cache:clear');

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

        $count = Ucases::destroy($id);

        if ( $count > 0 ) {

            Artisan::call('cache:clear');

            return response()->json([
                'status'  => 'success',
                'title'   => 'Đã xóa!',
                'message' => 'Đã xóa chức năng!',
            ]);
        }
        return response()->json([
            'status'  => 'error',
            'title'   => 'Lỗi!',
            'message' => 'Xóa chức năng không thành công!',
        ]);
    }
}
