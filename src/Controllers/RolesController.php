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
		return view( ovic_blade( 'Backend.roles.app' ) );
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
	public function roles( Request $request )
	{
		$totalData = Roles::count();

		$totalFiltered = $totalData;

		$limit  = $request->input( 'length' );
		$start  = $request->input( 'start' );
		$sort   = $request->input( 'sorting' );
		$search = $request->input( 'search.value' );
		$status = $request->input( 'columns.1.search.value' );

		$sorting = [
			[ 'id', '>', 0 ],
		];

		if ( $status != '' ) {
			$sorting = [
				[ 'status', '=', $status ],
			];
		}
		elseif ( $sort != '' && !empty( $search ) ) {
			$sorting = [
				[ 'status', '=', $sort ],
			];
		}

		if ( empty( $search ) ) {
			$roles = Roles::where( $sorting )
				->offset( $start )
				->limit( $limit )
				->orderBy( 'ordering', 'asc' )
				->get();
		}
		else {
			$roles = Roles::where( $sorting )
				->where(
					function ( $query ) use ( $search ) {
						$query->where( 'name', 'LIKE', "%{$search}%" )
							->orWhere( 'title', 'LIKE', "%{$search}%" )
							->orWhere( 'description', 'LIKE', "%{$search}%" );
					}
				)
				->offset( $start )
				->limit( $limit )
				->orderBy( 'ordering', 'asc' )
				->get();

			$totalFiltered = Roles::where( $sorting )
				->where(
					function ( $query ) use ( $search ) {
						$query->where( 'name', 'LIKE', "%{$search}%" )
							->orWhere( 'title', 'LIKE', "%{$search}%" )
							->orWhere( 'description', 'LIKE', "%{$search}%" );
					}
				)
				->count();
		}

		$data = array();

		if ( !empty( $roles ) ) {
			foreach ( $roles as $role ) {
				$options = "";

				if ( $role->status == 0 ) {
					$options .= "<a href='#' title='Mở khóa role' class='btn dim btn-danger lock'>";
					$options .= "<span class='fa fa-lock'></span>";
					$options .= "</a>";
				}
				else {
					$options .= "<a href='#' title='khóa role' class='btn dim btn-warning lock'>";
					$options .= "<span class='fa fa-unlock-alt'></span>";
					$options .= "</a>";
				}

				$options .= "<a href='#' title='Sửa role' class='btn dim btn-primary edit'>";
				$options .= "<span class='fa fa-pencil-square-o'></span>";
				$options .= "</a>";

				$options .= "<input type='hidden' name='role-{$role->id}' value='" . json_encode( $role ) . "'/>";

				$nestedData['name']        = $role->name;
				$nestedData['title']       = $role->title;
				$nestedData['description'] = $role->description;
				$nestedData['ordering']    = $role->ordering;
				$nestedData['status']      = $options;

				$data[] = $nestedData;
			}
		}

		$json_data = array(
			"draw"            => intval( $request->input( 'draw' ) ),
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $data,
		);

		return response()->json( $json_data );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request )
	{
		$validator = Validator::make( $request->all(),
			[
				'name'        => [ 'required', 'string', 'max:150' ],
				'title'       => [ 'required', 'string', 'max:150' ],
				'description' => [ 'string' ],
				'ordering'    => [ 'numeric', 'min:0' ],
				'status'      => [ 'numeric', 'min:0', 'max:1' ],
			]
		);

		if ( $validator->passes() ) {
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
					'message' => $role,
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
	 * @param int $id
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
	 * @param int $id
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
	 * @param \Illuminate\Http\Request $request
	 * @param int                      $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, $id )
	{
		$rules = [
			'description' => [ 'string' ],
			'ordering'    => [ 'numeric', 'min:0' ],
			'status'      => [ 'numeric', 'min:0', 'max:1' ],
		];
		if ( $request->has( 'name' ) ) {
			$rules['name'] = [ 'required', 'string', 'max:150', 'unique:roles,name,' . $id ];
		}
		if ( $request->has( 'title' ) ) {
			$rules['title'] = [ 'required', 'string', 'max:150' ];
		}

		$validator = Validator::make( $request->all(), $rules );

		if ( $validator->passes() ) {
			$data = $request->toArray();

			unset( $data['id'] );

			Roles::where( 'id', $id )->update( $data );

			return response()->json(
				[
					'status'  => 200,
					'message' => 'Update role thành công.',
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
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id )
	{
		$delete = Roles::find( $id );

		if ( !empty( $delete ) ) {
			$delete->delete();

			return response()->json(
				[
					'status'  => 'success',
					'title'   => 'Deleted!',
					'message' => 'Xóa role thành công.',
				]
			);
		}

		return response()->json(
			[
				'status'  => 'error',
				'title'   => 'Error!',
				'message' => 'Xóa role không thành công.',
			]
		);
	}
}
