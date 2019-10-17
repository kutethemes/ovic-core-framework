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
					'status'  => 'error',
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
					'status'  => 'error',
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
					'status'  => 'error',
					'message' => 'required Post ID',
				], 400
			);
		}

		$removed = Post::remove_post( $request->id );

		return response()->json( $removed, $removed['code'] );
	}
}