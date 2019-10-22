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

		$limit = $request->input( 'length' );
		$start = $request->input( 'start' );
		$order = $columns[$request->input( 'order.0.column' )];
		$dir   = $request->input( 'order.0.dir' );

		if ( empty( $request->input( 'search.value' ) ) ) {
			$users = User::offset( $start )
				->limit( $limit )
				->orderBy( $order, $dir )
				->get();
		} else {
			$search = $request->input( 'search.value' );

			$users = User::where( 'id', 'LIKE', "%{$search}%" )
				->orWhere( 'donvi', 'LIKE', "%{$search}%" )
				->orWhere( 'status', 'LIKE', "%{$search}%" )
				->offset( $start )
				->limit( $limit )
				->orderBy( $order, $dir )
				->get();

			$totalFiltered = User::where( 'id', 'LIKE', "%{$search}%" )
				->orWhere( 'donvi', 'LIKE', "%{$search}%" )
				->orWhere( 'status', 'LIKE', "%{$search}%" )
				->count();
		}

		$data = array();
		if ( !empty( $users ) ) {
			foreach ( $users as $user ) {
				$avatar_url = "img/a_none.jpg";
				$options    = "";

				if ( !empty( $user->avatar ) && $user->avatar > 0 ) {
					$path       = Post::find( $user->avatar )->value( 'name' );
					$avatar_url = route( 'get_file', explode( '/', $path ) );
				}

				$user['avatar_url'] = $avatar_url;

				$avatar = "<img alt='avatar' src='{$avatar_url}'>";

				$options .= "<a href='#' title='Khóa' class='btn dim btn-warning lock'>";
				if ( $user->status == 0 ) {
					$options .= "<span class='fa fa-unlock-alt'></span>";
				} else {
					$options .= "<span class='fa fa-lock'></span>";
				}
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
		$request = $request->toArray();

		User::where( 'id', $id )->update( $request );

		return response()->json(
			[
				'message' => 'Update thành công.',
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
