<?php
/**
 * Get the evaluated view contents for the given view.
 *
 * @param string|null $view
 *
 * @return string
 */
function ovic_blade( $view = null )
{
	if ( !view()->exists( $view ) ) {
		$view = "ovic::{$view}";
	}

	return $view;
}

/**
 * Parses a string into variables to be stored in an array.
 *
 * Uses {@link https://secure.php.net/parse_str parse_str()} and stripslashes if
 * {@link https://secure.php.net/magic_quotes magic_quotes_gpc} is on.
 *
 * @param string $string The string to be parsed.
 * @param array  $array Variables will be stored in this array.
 *
 * @since 2.2.1
 *
 */
function ovic_parse_str( $string, &$array )
{
	parse_str( $string, $array );
	if ( get_magic_quotes_gpc() ) {
		$array = stripslashes_deep( $array );
	}
}

/**
 * Merge user defined arguments into defaults array.
 *
 * This function is used throughout WordPress to allow for both string or array
 * to be merged into another array.
 *
 * @param string|array|object $args Value to merge with $defaults.
 * @param array               $defaults Optional. Array that serves as the defaults. Default empty.
 *
 * @return array Merged user defined values with defaults.
 * @since 2.3.0 `$args` can now also be an object.
 *
 * @since 2.2.0
 */
function ovic_parse_args( $args, $defaults = '' )
{
	if ( is_object( $args ) ) {
		$r = get_object_vars( $args );
	} elseif ( is_array( $args ) ) {
		$r =& $args;
	} else {
		ovic_parse_str( $args, $r );
	}

	if ( is_array( $defaults ) ) {
		return array_merge( $defaults, $r );
	}

	return $r;
}

/**
 * Retrieves a post meta field for the given post ID.
 *
 * @param int    $post_id Post ID.
 * @param string $key Optional. The meta key to retrieve. By default, returns
 *                        data for all keys. Default empty.
 *
 * @return mixed Will be an array if $single is false. Will be value of the meta
 *               field if $single is true.
 * @since 1.0.0
 *
 */
function ovic_post_meta( $post_id, $meta_key )
{
	return \Ovic\Framework\Postmeta::post_meta( $post_id, $meta_key );
}

/**
 * Retrieve user meta field for a user.
 *
 * @param int    $user_id User ID.
 * @param string $key Optional. The meta key to retrieve. By default, returns data for all keys.
 *
 * @return mixed Will be an array if $single is false. Will be value of meta data field if $single is true.
 * @since 1.0.0
 *
 */
function ovic_user_meta( $user_id, $meta_key )
{
	return \Ovic\Framework\Usermeta::user_meta( $user_id, $meta_key );
}