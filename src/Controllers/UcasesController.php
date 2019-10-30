<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class UcasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(ovic_blade('Backend.ucases.app'));
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
    public function ucases(Request $request)
    {
        $totalData = Ucases::count();

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
        } elseif ($sort != '' && !empty($search)) {
            $sorting = [
                ['status', '=', $sort],
            ];
        }

        if (empty($search)) {
            $ucases = Ucases::where($sorting)
                ->offset($start)
                ->limit($limit)
                ->orderBy('ordering', 'asc')
                ->get()
                ->toArray();
        } else {
            $ucases = Ucases::where($sorting)
                ->where(
                    function ($query) use ($search) {
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
                    function ($query) use ($search) {
                        $query->where('title', 'LIKE', "%{$search}%")
                            ->orWhere('slug', 'LIKE', "%{$search}%");
                    }
                )
                ->count();
        }

        $data = [];

        if (!empty($ucases)) {
            foreach ($ucases as $ucase) {
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

    public function ucase_data($ucase)
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
    public function store(Request $request)
    {
        $dataTable = [];
        $validator = Validator::make($request->all(), [
            'slug'      => ['required', 'string', 'max:255'],
            'title'     => ['required', 'string', 'max:255'],
            'ordering'  => ['numeric', 'min:0'],
            'parent_id' => ['numeric', 'min:0'],
            'access'    => ['numeric', 'min:0', 'max:3'],
            'status'    => ['numeric', 'min:0', 'max:3'],
            'router.*'  => ['string', 'nullable'],
        ]);

        if ($validator->passes()) {
            $data           = $request->toArray();
            $router         = array_map('htmlentities', $data['router']);
            $data['router'] = html_entity_decode(json_encode($router));

            $ucase = new Ucases();

            $ucase->slug      = $data['slug'];
            $ucase->title     = $data['title'];
            $ucase->router    = !empty($data['router']) ? $data['router'] : '';
            $ucase->position  = !empty($data['position']) ? $data['position'] : 'left';
            $ucase->parent_id = !empty($data['parent_id']) ? $data['parent_id'] : 0;
            $ucase->ordering  = !empty($data['ordering']) ? $data['ordering'] : 99;
            $ucase->access    = !empty($data['access']) ? $data['access'] : 1;
            $ucase->status    = !empty($data['status']) ? $data['status'] : 1;

            $ucase->save();

            $ucase_id = $ucase->getAttributeValue('id');

            if ($request->has('dataTable')) {
                $ucase = Ucases::where('id', $ucase_id)->get()->first();

                if (!empty($ucase)) {
                    $dataTable = $this->ucase_data($ucase);
                }
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Tạo chức năng thành công.',
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
            'ordering'  => ['numeric', 'min:0'],
            'parent_id' => ['numeric', 'min:0'],
            'access'    => ['numeric', 'min:0', 'max:3'],
            'status'    => ['numeric', 'min:0', 'max:3'],
            'router.*'  => ['string', 'nullable'],
        ];
        if ($request->has('name')) {
            $rules['slug'] = ['required', 'string', 'max:255', 'unique:ucases,slug,'.$id];
        }
        if ($request->has('title')) {
            $rules['title'] = ['required', 'string', 'max:255'];
        }
        $validator = Validator::make($request->all(), $rules);
        $data      = $request->except(['_token', 'id', 'dataTable']);

        if ($validator->passes()) {
            if (!empty($data['router'])) {
                $router         = array_map('htmlentities', $data['router']);
                $data['router'] = html_entity_decode(json_encode($router));
            }

            Ucases::where('id', $id)->update($data);

            if ($request->has('dataTable')) {
                $ucase = Ucases::where('id', $id)->get()->first();

                if (!empty($ucase)) {
                    $dataTable = $this->ucase_data($ucase);
                }
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Update chức năng thành công.',
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
    public function destroy($id)
    {
        $delete = Ucases::find($id);

        if (!empty($delete)) {
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
