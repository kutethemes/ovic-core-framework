<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{
	/**
	 * Saving images uploaded through XHR Request.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function upload( Request $request )
	{
		if ( !$request->hasFile( 'file' ) ) {
			return response()->json(
				[
					'status'  => 'warning',
					'message' => 'The File do not exits.',
				], 400
			);
		}

		$now  = now();
		$file = $request->file( 'file' );

		$MimeType     = $file->getClientMimeType();
		$extension    = $file->getClientOriginalExtension();
		$FileSize     = $file->getSize();
		$OriginalName = $file->getClientOriginalName();

		$FileName  = str_replace( ".{$extension}", "-{$now->getTimestamp()}.{$extension}", $OriginalName );
		$FileTitle = str_replace( ".{$extension}", "", $FileName );

		$FilePath = Storage::putFileAs(
			"/uploads/{$now->year}/{$now->month}",
			$file,
			$FileName
		);

		$created = \Ovic\Framework\Post::add_post(
			[
				'title'     => $FileTitle,
				'name'      => $FileName,
				'post_type' => 'attachment',
				'meta'      => [
					'_attachment_meta' => [
						'alt'       => '',
						'size'      => $FileSize,
						'mimetype'  => $MimeType,
						'extension' => $extension,
						'path'      => $FilePath,
					],
				],
				'user_id'   => Auth::user()->id,
				'owner_id'  => Auth::user()->id,
			]
		);

		$file_url = url( Storage::url( "app/{$FilePath}" ) );

		if ( strstr( $MimeType, "video/" ) ) {
			$FileType = '<div class="icon"><i class="img-fluid fa fa-film"></i></div>';
		} else if ( strstr( $MimeType, "image/" ) ) {
			$FileType = '<div class="image"><img alt="image" class="img-fluid" src="' . $file_url . '"></i></div>';
		} else if ( strstr( $MimeType, "audio/" ) ) {
			$FileType = '<div class="icon"><i class="fa fa-music"></i></div>';
		} else if ( strstr( $MimeType, "xls" ) ) {
			$FileType = '<div class="icon"><i class="fa fa-bar-chart-o"></i></div>';
		} else {
			$FileType = '<div class="icon"><i class="fa fa-file"></i></div>';
		}

		return response()->json(
			[
				'message'  => 'Image saved Successfully',
				'name'     => $FileName,
				'size'     => $FileSize,
				'type'     => $FileType,
				'post_id'  => $created['post_id'],
				'url'      => $file_url,
				'datetime' => $now->toDateTimeString(),
			]
		);
	}

	/**
	 * Remove the images from the storage.
	 *
	 * @param Request $request
	 */
	public function destroy( Request $request )
	{
		return response()->json( [ 'message' => 'File successfully delete' ] );
	}
}