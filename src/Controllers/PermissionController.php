<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu_left = Ucases::where(
            [
                [ 'position', 'left' ],
                [ 'status', '1' ]
            ]
        )->get([ 'id', 'title', 'parent_id', 'ordering', 'router' ])
            ->collect()
            ->sortBy('ordering')
            ->groupBy('parent_id')
            ->toArray();

        $menu_top = Ucases::where(
            [
                [ 'position', 'top' ],
                [ 'status', '1' ]
            ]
        )->get([ 'id', 'title', 'parent_id', 'ordering', 'router' ])
            ->collect()
            ->sortBy('ordering')
            ->groupBy('parent_id')
            ->toArray();

        $roles = Roles::where('status', '1')
            ->get([ 'id', 'title', 'ucase_ids', 'created_at', 'description' ])
            ->collect()
            ->sortBy('ordering')
            ->toArray();

        $menus = [
            'menu-left' => $menu_left,
            'menu-top'  => $menu_top,
        ];

        return view(ovic_blade('Backend.permission.app'), compact([ 'menus', 'roles' ]));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        //
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
        $validator = Validator::make($request->all(), [
            'id' => [ 'required', 'numeric', 'unique:roles,id,'.$id ],
        ]);
        $data      = $request->except([ '_token', 'id' ]);

        if ( $validator->passes() ) {
            $ucase_ids = Roles::where('id', $id)
                ->value('ucase_ids');

            $ucase_ids = !empty($ucase_ids) ? json_decode($ucase_ids, true) : [];

            if ( !empty($data) ) {
                foreach ( $data as $key => $value ) {
                    if ( count(array_keys($value, '0')) == count($value) ) {
                        unset($ucase_ids[$key]);
                    } else {
                        $ucase_ids[$key] = $value;
                    }
                }
            }

            $count     = count($ucase_ids);
            $ucase_ids = json_encode($ucase_ids);

            /* update */
            Roles::where('id', $id)->update([
                'ucase_ids' => $ucase_ids
            ]);

            return response()->json([
                'status'  => 200,
                'message' => 'Phân quyền thành công.',
                'data'    => $ucase_ids,
                'count'   => $count,
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
        //
    }
}
