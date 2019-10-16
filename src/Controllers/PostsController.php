<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
				]
			);
		}

		$name = !empty( $request->name ) ? $request->name : Str::slug( $request->title );

		if ( Post::is_exits( $name ) ) {
			return response()->json(
				[
					'status'  => 'warning',
					'message' => 'The post is exits.',
				]
			);
		}

		$post = new Post();

		$post->name      = $name;
		$post->title     = $request->title;
		$post->post_type = maybe_serialize( $request->post_type );
		$post->content   = !empty( $request->desc ) ? $request->desc : '';
		$post->status    = !empty( $request->status ) ? maybe_serialize( $request->status ) : 'publish';

		$post->save();

		$post_id = $post->getAttributeValue( 'id' );

		if ( !empty( $request->meta ) && !empty( $request->meta['meta_key'] ) ) {
			$meta             = new Postmeta();
			$meta->post_id    = $post_id;
			$meta->meta_key   = maybe_serialize( $request->meta['meta_key'] );
			$meta->meta_value = !empty( $request->meta['meta_value'] ) ? maybe_serialize( $request->meta['meta_value'] ) : '';

			$meta->save();
		}

		return response()->json(
			[
				'status'  => 'success',
				'message' => 'The post is created.',
			]
		);
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
				]
			);
		}

		if ( !Post::is_exits( $request->id ) ) {
			return response()->json(
				[
					'status'  => 'warning',
					'message' => 'The post is do not exits.',
				]
			);
		}

		$input = $request->only(
			[
				'title',
				'name',
				'content',
				'status',
				'post_type',
				'created_at',
				'updated_at',
			]
		);

		if ( !empty( $input ) ) {
			foreach ( $input as $key => $value ) {
				$input[$key] = maybe_serialize( $value );
			}

			Post::where( 'id', $request->id )->update( $input );

			if ( !empty( $request->meta ) ) {
				foreach ( $request->meta as $meta_key => $meta_value ) {
					Postmeta::where( 'meta_key', $meta_key )
						->where( 'post_id', $request->id )
						->update(
							[
								'meta_value' => !empty( $meta_value ) ? maybe_serialize( $meta_value ) : '',
							]
						);
				}
			}
		}

		return response()->json(
			[
				'status'  => 'success',
				'message' => 'The post is updated.',
			]
		);
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
				]
			);
		}

		if ( !Post::is_exits( $request->id ) ) {
			return response()->json(
				[
					'status'  => 'warning',
					'message' => 'The post is do not exits.',
				]
			);
		}

		Post::find( $request->id )->delete();
		Postmeta::where( 'post_id', $request->id )->delete();

		return response()->json(
			[
				'status'  => 'success',
				'message' => 'The post is removed.',
			]
		);
	}
}