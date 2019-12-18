<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Doituong\Entities\Diachi;

class ProfileController extends Controller
{
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
            name_blade('Backend.profile.app'),
            [
                'user'       => Auth::user(),
                'permission' => $permission,
                'diachi'     => Diachi::select('id', 'parent_id', 'tendiadanh')
                    ->where('parent_id', 1)
                    ->get(),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return void
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
     * @return void
     */
    public function update( Request $request, $id )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy( $id )
    {
        //
    }
}
