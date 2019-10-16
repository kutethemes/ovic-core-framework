<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

class Post extends Eloquent
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/*
	 * @param: $post is name/slug/id
	 * */
	public static function is_exits( $post )
	{
		$column = 'id';
		if ( !abs( intval( $post ) ) ) {
			$column = 'name';
		}
		$post = Post::where( $column, '=', $post )->first();
		if ( $post === null ) {
			return false;
		}

		return true;
	}

	public static function update_post( $request )
	{
		$data    = [];
		$input   = [
			'name',
			'title',
			'content',
			'status',
			'post_type',
			'user_id',
			'owner_id',
			'created_at',
			'updated_at',
		];
		$post_id = $request['id'];

		if ( !Post::is_exits( $post_id ) ) {
			return [
				'code'    => 400,
				'status'  => 'warning',
				'message' => 'The post is do not exits.',
			];
		}

		if ( !empty( $request ) ) {
			foreach ( $request as $key => $value ) {
				if ( in_array( $key, $input ) ) {
					$data[$key] = maybe_serialize( $value );
				}
			}

			Post::where( 'id', $post_id )->update( $data );

			if ( !empty( $request['meta'] ) ) {
				foreach ( $request['meta'] as $meta_key => $meta_value ) {
					Postmeta::where( 'meta_key', $meta_key )
						->where( 'post_id', $post_id )
						->update(
							[
								'meta_value' => maybe_serialize( $meta_value ),
							]
						);
				}
			}
		}

		return [
			'code'    => 200,
			'status'  => 'success',
			'message' => 'The post is updated.',
		];
	}

	public static function add_post( $request )
	{
		$name = !empty( $request['name'] ) ? $request['name'] : Str::slug( $request['title'] );

		if ( Post::is_exits( $name ) ) {
			return [
				'code'    => 400,
				'post_id' => 0,
				'status'  => 'warning',
				'message' => 'The post is exits.',
			];
		}

		$post = new Post();

		$post->name      = $name;
		$post->title     = $request['title'];
		$post->post_type = maybe_serialize( $request['post_type'] );
		$post->content   = !empty( $request['content'] ) ? $request['content'] : '';
		$post->user_id   = !empty( $request['user_id'] ) ? $request['user_id'] : 0;
		$post->owner_id  = !empty( $request['owner_id'] ) ? $request['owner_id'] : 0;
		$post->status    = !empty( $request['status'] ) ? maybe_serialize( $request['status'] ) : 'publish';

		$post->save();

		$post_id = $post->getAttributeValue( 'id' );

		if ( !empty( $request['meta'] ) ) {
			foreach ( $request['meta'] as $meta_key => $meta_value ) {
				$meta             = new Postmeta();
				$meta->post_id    = $post_id;
				$meta->meta_key   = maybe_serialize( $meta_key );
				$meta->meta_value = maybe_serialize( $meta_value );

				$meta->save();
			}
		}

		return [
			'code'    => 200,
			'post_id' => $post_id,
			'status'  => 'success',
			'message' => 'The post is created.',
		];
	}

	/*
	 * @param: $post is name/slug/id
	 * */
	public static function get_post( $post, $include_meta = true )
	{
		$column = 'id';
		if ( !abs( intval( $post ) ) ) {
			$column = 'name';
		}
		$post = Post::where( $column, '=', $post )->first();
		$post = json_decode( $post->toJson(), true );
		if ( $include_meta ) {
			$post_meta = Postmeta::get_meta( $post['id'] );
			if ( !empty( $post_meta ) ) {
				$post['meta'] = $post_meta;
			}
		}

		return $post;
	}
}