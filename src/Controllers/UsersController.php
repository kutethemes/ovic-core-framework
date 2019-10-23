<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view( ovic_blade( 'Backend.users.app' ) );
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
		$columns = array(
			0 => 'avatar',
			1 => 'name',
			2 => 'donvi_id',
			3 => 'email',
			4 => 'status',
		);

		$totalData = User::count();

		$totalFiltered = $totalData;

		$limit  = $request->input( 'length' );
		$start  = $request->input( 'start' );
		$sort   = $request->input( 'sorting' );
		$search = $request->input( 'search.value' );
		$status = $request->input( 'columns.4.search.value' );
		/*
		$order = $columns[$request->input( 'order.0.column' )];
		$dir   = $request->input( 'order.0.dir' );
		*/

		$sorting = [
			[ 'id', '!=', 0 ],
		];

		if ( $status != '' ) {
			$sorting = [
				[ 'status', '=', $status ],
			];
		} elseif ( $sort != '' && !empty( $search ) ) {
			$sorting = [
				[ 'status', '=', $sort ],
				[ 'name', 'LIKE', "%{$search}%" ],
				[ 'email', 'LIKE', "%{$search}%" ],
			];
		}

		if ( empty( $search ) ) {
			$users = User::where( $sorting )
				->offset( $start )
				->limit( $limit )
				/* ->orderBy( $order, $dir ) */
				->latest()
				->get();
		} else {
			$users = User::where( $sorting )
				->where(
					function ( $query ) use ( $search ) {
						$query->where( 'name', 'LIKE', "%{$search}%" )
							->orWhere( 'email', 'LIKE', "%{$search}%" );
					}
				)
				->offset( $start )
				->limit( $limit )
				/* ->orderBy( $order, $dir ) */
				->latest()
				->get();

			$totalFiltered = User::where( $sorting )
				->where(
					function ( $query ) use ( $search ) {
						$query->where( 'name', 'LIKE', "%{$search}%" )
							->orWhere( 'email', 'LIKE', "%{$search}%" );
					}
				)
				->count();
		}

		$data = array();

		if ( !empty( $users ) ) {
			foreach ( $users as $user ) {
				$avatar_url  = "img/a_none.jpg";
				$options     = "";
				$status_html = "";
				$status_txt  = "Mở khóa user";

				if ( !empty( $user->avatar ) && $user->avatar > 0 ) {
					$path       = Post::where( 'id', '=', $user->avatar )->value( 'name' );
					$avatar_url = route( 'get_file', explode( '/', $path ) );
				}

				if ( $user->status == 0 ) {
					$status_html .= "<span class='fa fa-lock'></span>";
				} else {
					$status_txt  = "Khoá user";
					$status_html .= "<span class='fa fa-unlock-alt'></span>";
				}

				$user['avatar_url'] = $avatar_url;

				$avatar = "<img alt='avatar' src='{$avatar_url}'>";

				$options .= "<a href='#' title='{$status_txt}' class='btn dim btn-warning lock'>";
				$options .= "{$status_html}";
				$options .= "</a>";

				$options .= "<a href='#' title='Sửa' class='btn dim btn-primary edit'>";
				$options .= "<span class='fa fa-pencil-square-o'></span>";
				$options .= "</a>";

				$options .= "<input type='hidden' name='user-{$user->id}' value='" . json_encode( $user ) . "'/>";

				$nestedData['avatar']   = $avatar;
				$nestedData['name']     = $user->name;
				$nestedData['donvi_id'] = $user->donvi_id;
				$nestedData['email']    = $user->email;
				$nestedData['status']   = $options;

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
	 * Get a validator for an incoming registration request.
	 *
	 * @param array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function store( Request $request )
	{
		$validator = Validator::make( $request->all(),
			[
				'name'     => [ 'required', 'string', 'max:255' ],
				'email'    => [ 'required', 'string', 'email', 'max:255', 'unique:users' ],
				'password' => [ 'required', 'string', 'min:8', 'confirmed' ],
			]
		);

		if ( $validator->passes() ) {
			$data = $request->toArray();

			$user = new User();

			$user->name      = $data['name'];
			$user->email     = $data['email'];
			$user->avatar    = $data['avatar'];
			$user->password  = Hash::make( $data['password'] );
			$user->donvi_id  = !empty( $data['donvi_id'] ) ? $data['donvi_id'] : null;
			$user->donvi_ids = !empty( $data['donvi_ids'] ) ? maybe_serialize( $data['donvi_ids'] ) : null;
			$user->role_ids  = !empty( $data['role_ids'] ) ? maybe_serialize( $data['role_ids'] ) : null;
			$user->status    = $data['status'];

			$user->save();

			return response()->json(
				[
					'status'  => 200,
					'message' => $user,
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
		$validator = Validator::make( $request->all(),
			[
				'name'     => [ 'required', 'string', 'max:255' ],
				'email'    => [ 'required', 'string', 'email', 'max:255', 'unique:users' ],
				'password' => [ 'string', 'min:8' ],
			]
		);

		if ( $validator->passes() ) {
			$data = $request->toArray();

			unset( $data['id'] );

			if ( !empty( $data['password'] ) ) {
				$data['password'] = Hash::make( $data['password'] );
			}

			if ( !empty( $data['donvi_ids'] ) ) {
				$data['donvi_ids'] = maybe_serialize( $data['donvi_ids'] );
			}

			if ( !empty( $data['role_ids'] ) ) {
				$data['role_ids'] = maybe_serialize( $data['role_ids'] );
			}

			User::where( 'id', $id )->update( $data );

			return response()->json(
				[
					'status'  => 200,
					'message' => 'Update thành công.',
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
		$delete = User::find( $id );

		if ( !empty( $delete ) ) {
			$delete->delete();

			return response()->json(
				[
					'status'  => 'success',
					'title'   => 'Deleted!',
					'message' => 'Xóa user thành công.',
				]
			);
		}

		return response()->json(
			[
				'status'  => 'error',
				'title'   => 'Error!',
				'message' => 'Xóa user không thành công.',
			]
		);
	}
}
