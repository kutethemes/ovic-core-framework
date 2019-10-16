<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Model as Eloquent;

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
				$post['metas'] = $post_meta;
			}
		}

		return $post;
	}
}