<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(ovic_blade('Backend.roles.app'));
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
     * Show the role list a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles(Request $request)
    {
        $totalData = Roles::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $sort   = $request->input('sorting');
        $search = $request->input('search.value');
        $status = $request->input('columns.1.search.value');

        $sorting = [
            ['id', '>', 0],
        ];

        if ($status != '') {
            $sorting = [
                ['status', '=', $status],
            ];
        } elseif ($sort != '' && ! empty($search)) {
            $sorting = [
                ['status', '=', $sort],
            ];
        }

        if (empty($search)) {
            $roles = Roles::where($sorting)
                          ->offset($start)
                          ->limit($limit)
                          ->orderBy('ordering', 'asc')
                          ->get()
                          ->toArray()
            ;
        } else {
            $roles = Roles::where($sorting)
                          ->where(
                              function ($query) use ($search) {
                                  $query->where('name', 'LIKE', "%{$search}%")
                                        ->orWhere('title', 'LIKE', "%{$search}%")
                                        ->orWhere('description', 'LIKE', "%{$search}%")
                                  ;
                              }
                          )
                          ->offset($start)
                          ->limit($limit)
                          ->orderBy('ordering', 'asc')
                          ->get()
                          ->toArray()
            ;

            $totalFiltered = Roles::where($sorting)
                                  ->where(
                                      function ($query) use ($search) {
                                          $query->where('name', 'LIKE', "%{$search}%")
                                                ->orWhere('title', 'LIKE', "%{$search}%")
                                                ->orWhere('description', 'LIKE', "%{$search}%")
                                          ;
                                      }
                                  )
                                  ->count()
            ;
        }

        $data = [];

        if ( ! empty($roles)) {
            foreach ($roles as $role) {
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

    public function role_data($role)
    {
        $role['status'] = $role['status'] < 0 ? 0 : $role['status'];

        return $role;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name'        => ['required', 'string', 'max:150'],
                'title'       => ['required', 'string', 'max:150'],
                'description' => ['string'],
                'ordering'    => ['numeric', 'min:0'],
                'status'      => ['numeric', 'min:0', 'max:1'],
            ]
        );

        if ($validator->passes()) {
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        $dataTable = [];
        $rules     = [
            'description' => ['string'],
            'ordering'    => ['numeric', 'min:0'],
            'status'      => ['numeric', 'min:0', 'max:1'],
        ];
        if ($request->has('name')) {
            $rules['name'] = ['required', 'string', 'max:150', 'unique:roles,name,'.$id];
        }
        if ($request->has('title')) {
            $rules['title'] = ['required', 'string', 'max:150'];
        }

        $validator = Validator::make($request->all(), $rules);
        $data      = $request->except(['_token', 'id', 'dataTable']);

        if ($validator->passes()) {
            /* update */
            Roles::where('id', $id)->update($data);

            if ($request->has('dataTable')) {
                $role = Roles::where('id', $id)->get()->first();

                if ( ! empty($role)) {
                    $dataTable = $this->role_data($role);
                }
            }

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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Roles::find($id);

        if ( ! empty($delete)) {
            $delete->delete();

            return response()->json(
                [
                    'status'  => 'success',
                    'title'   => 'Deleted!',
                    'message' => 'Xóa nhóm thành công.',
                ]
            );
        }

        return response()->json(
            [
                'status'  => 'error',
                'title'   => 'Error!',
                'message' => 'Xóa nhóm không thành công.',
            ]
        );
    }
}
