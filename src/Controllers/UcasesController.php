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
        'router.*'  => [ 'string', 'nullable' ],
    ];
    private $messages = [
        'slug.required'  => 'Tên router là trường bắt buộc tối đa 100 kí tự',
        'title.required' => 'Tên hiển thị là trường bắt buộc tối đa 100 kí tự',
        'access.max'     => 'Quyền truy cập chấp nhận 3 ký tự số 0, 1 và 2',
        'status.max'     => 'Trạng thái chấp nhận 3 ký tự số 0, 1 và 2',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = [
            'menu-left' => Ucases::Menus('left'),
            'menu-top'  => Ucases::Menus('top'),
        ];

        return view(ovic_blade('Backend.ucases.app'), compact('menus'));
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
     * Show the ucase list a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ucases( Request $request )
    {
        $totalData = Ucases::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $sort   = $request->input('sorting');
        $search = $request->input('search.value');
        $status = $request->input('columns.1.search.value');

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
            $ucases = Ucases::where($sorting)
                ->offset($start)
                ->limit($limit)
                ->orderBy('ordering', 'asc')
                ->get()
                ->toArray();
        } else {
            $ucases = Ucases::where($sorting)
                ->where(
                    function ( $query ) use ( $search ) {
                        $query->where('title', 'LIKE', "%{$search}%")
                            ->orWhere('slug', 'LIKE', "%{$search}%");
                    }
                )
                ->offset($start)
                ->limit($limit)
                ->orderBy('ordering', 'asc')
                ->get()
                ->toArray();

            $totalFiltered = Ucases::where($sorting)
                ->where(
                    function ( $query ) use ( $search ) {
                        $query->where('title', 'LIKE', "%{$search}%")
                            ->orWhere('slug', 'LIKE', "%{$search}%");
                    }
                )
                ->count();
        }

        $data = [];

        if ( !empty($ucases) ) {
            foreach ( $ucases as $ucase ) {
                $data[] = $this->ucase_data($ucase);
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

    public function ucase_data( $ucase )
    {
        $ucase['router'] = json_decode($ucase['router'], true);

        return $ucase;
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
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ( $validator->passes() ) {
            $data           = $request->toArray();
            $router         = array_map('htmlentities', $data['router']);
            $data['router'] = html_entity_decode(json_encode($router));
            $ordering       = Ucases::where([
                [ 'parent_id', 0 ],
                [ 'position', $data['position'] ],
            ])->count();

            $ucase = new Ucases();

            $ucase->slug      = $data['slug'];
            $ucase->title     = $data['title'];
            $ucase->ordering  = $ordering;
            $ucase->parent_id = 0;
            $ucase->router    = !empty($data['router']) ? $data['router'] : '';
            $ucase->position  = !empty($data['position']) ? $data['position'] : 'left';
            $ucase->access    = !empty($data['access']) ? $data['access'] : 1;
            $ucase->status    = !empty($data['status']) ? $data['status'] : 1;

            $ucase->save();

            $ucase_id = $ucase->getAttributeValue('id');

            return response()->json([
                'status'  => 200,
                'message' => 'Tạo chức năng thành công.',
                'id'      => $ucase_id,
                'icon'    => $router['icon'],
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
            $edit['router'] = json_decode($edit['router'], true);
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

    public function order( Request $request )
    {
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
                'message' => 'Cập nhật ordering thành công.',
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
        $this->rules['slug'] = '';
        if ( $request->has('name') ) {
            $this->rules['slug'] = [ 'required', 'string', 'max:100', 'unique:ucases,slug,'.$id ];
        }
        if ( !$request->has('title') ) {
            $this->rules['title'] = '';
        }
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $data      = $request->except([ '_token', 'id' ]);

        if ( $validator->passes() ) {
            if ( !empty($data['router']) ) {
                $router         = array_map('htmlentities', $data['router']);
                $data['router'] = html_entity_decode(json_encode($router));
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
