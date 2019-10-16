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
 * Unserialize value only if it was serialized.
 *
 * @param string $original Maybe unserialized original, if is needed.
 *
 * @return mixed Unserialized data can be any type.
 * @since 2.0.0
 *
 */
function maybe_unserialize( $original )
{
	if ( is_serialized( $original ) ) { // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize( $original );
	}

	return $original;
}

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @param string $data Value to check to see if was serialized.
 * @param bool   $strict Optional. Whether to be strict about the end of the string. Default true.
 *
 * @return bool False if not serialized and true if it was.
 * @since 2.0.5
 *
 */
function is_serialized( $data, $strict = true )
{
	// if it isn't a string, it isn't serialized.
	if ( !is_string( $data ) ) {
		return false;
	}
	$data = trim( $data );
	if ( 'N;' == $data ) {
		return true;
	}
	if ( strlen( $data ) < 4 ) {
		return false;
	}
	if ( ':' !== $data[1] ) {
		return false;
	}
	if ( $strict ) {
		$lastc = substr( $data, -1 );
		if ( ';' !== $lastc && '}' !== $lastc ) {
			return false;
		}
	} else {
		$semicolon = strpos( $data, ';' );
		$brace     = strpos( $data, '}' );
		// Either ; or } must exist.
		if ( false === $semicolon && false === $brace ) {
			return false;
		}
		// But neither must be in the first X characters.
		if ( false !== $semicolon && $semicolon < 3 ) {
			return false;
		}
		if ( false !== $brace && $brace < 4 ) {
			return false;
		}
	}
	$token = $data[0];
	switch ( $token ) {
		case 's':
			if ( $strict ) {
				if ( '"' !== substr( $data, -2, 1 ) ) {
					return false;
				}
			} elseif ( false === strpos( $data, '"' ) ) {
				return false;
			}
		// or else fall through
		case 'a':
		case 'O':
			return (bool)preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b':
		case 'i':
		case 'd':
			$end = $strict ? '$' : '';

			return (bool)preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
	}

	return false;
}

/**
 * Check whether serialized data is of string type.
 *
 * @param string $data Serialized data.
 *
 * @return bool False if not a serialized string, true if it is.
 * @since 2.0.5
 *
 */
function is_serialized_string( $data )
{
	// if it isn't a string, it isn't a serialized string.
	if ( !is_string( $data ) ) {
		return false;
	}
	$data = trim( $data );
	if ( strlen( $data ) < 4 ) {
		return false;
	} elseif ( ':' !== $data[1] ) {
		return false;
	} elseif ( ';' !== substr( $data, -1 ) ) {
		return false;
	} elseif ( $data[0] !== 's' ) {
		return false;
	} elseif ( '"' !== substr( $data, -2, 1 ) ) {
		return false;
	} else {
		return true;
	}
}

/**
 * Serialize data, if needed.
 *
 * @param string|array|object $data Data that might be serialized.
 *
 * @return mixed A scalar data
 * @since 2.0.5
 *
 */
function maybe_serialize( $data )
{
	if ( is_array( $data ) || is_object( $data ) ) {
		return serialize( $data );
	}

	// Double serialization is required for backward compatibility.
	// See https://core.trac.wordpress.org/ticket/12930
	// Also the world will end. See WP 3.6.1.
	if ( is_serialized( $data, false ) ) {
		return serialize( $data );
	}

	return $data;
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