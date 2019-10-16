<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostsController extends Controller
{
	/**
	 * Create file for the posts.
	 *
	 * @param Request $request
	 */
	public function create( Request $request )
	{
		if ( !$request->has( [ 'title', 'post_type' ] ) ) {
			return response()->json(
				[
					'status'  => 'warning',
					'message' => 'required Title, Post Type',
				], 400
			);
		}

		$request = $request->toArray();

		$created = Post::add_post( $request );

		return response()->json( $created, $created['code'] );
	}

	/**
	 * Update file for the posts.
	 *
	 * @param Request $request
	 */
	public function update( Request $request )
	{
		if ( !$request->has( [ 'id' ] ) ) {
			return response()->json(
				[
					'status'  => 'warning',
					'message' => 'required Post ID',
				], 400
			);
		}

		$request = $request->toArray();

		$updated = Post::update_post( $request );

		return response()->json( $updated, $updated['code'] );
	}

	/**
	 * Remove file for the posts.
	 *
	 * @param Request $request
	 */
	public function remove( Request $request )
	{
		if ( !$request->has( [ 'id' ] ) ) {
			return response()->json(
				[
					'status'  => 'warning',
					'message' => 'required Post ID',
				], 400
			);
		}

		if ( !Post::is_exits( $request->id ) ) {
			return response()->json(
				[
					'status'  => 'warning',
					'message' => 'The post is do not exits.',
				], 400
			);
		}

		Postmeta::where( 'post_id', $request->id )->delete();

		return response()->json(
			[
				'status'  => Post::destroy( $request->id ),
				'message' => 'The post is removed.',
			]
		);
	}
}