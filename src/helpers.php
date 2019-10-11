<?php
if ( !function_exists( 'ovic_blade' ) ) {
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
}
if ( !function_exists( 'ovic_script' ) ) {
	/**
	 * Generate an asset path for the application.
	 *
	 * @param string    $path
	 * @param bool|null $secure
	 *
	 * @return string
	 */
	function ovic_script( $path, $secure = null )
	{
		$path = config( "ovic.scripts.{$path}" );

		if ( !empty( $path ) && file_exists( public_path( $path ) ) ) {
			return asset(
				$path,
				$secure
			);
		}

		return '';
	}
}
if ( !function_exists( 'ovic_style' ) ) {
	/**
	 * Generate an asset path for the application.
	 *
	 * @param string    $path
	 * @param bool|null $secure
	 *
	 * @return string
	 */
	function ovic_style( $path, $secure = null )
	{
		$path = config( "ovic.styles.{$path}" );

		if ( !empty( $path ) && file_exists( public_path( $path ) ) ) {
			return asset(
				$path,
				$secure
			);
		}

		return '';
	}
}