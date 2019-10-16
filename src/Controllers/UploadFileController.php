<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{
	/**
	 * Display all of the images.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return null;
	}

	/**
	 * Show the form for creating uploading new images.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return null;
	}

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

		$now  = Carbon::now();
		$file = $request->file( 'file' );
		$time = "{$now->toDateString()}_{$now->toTimeString()}";

		$MimeType     = $file->getClientMimeType();
		$extension    = $file->getClientOriginalExtension();
		$FileSize     = $file->getSize();
		$OriginalName = $file->getClientOriginalName();

		$FileName = str_replace( ".{$extension}", "-{$time}.{$extension}", $OriginalName );

		$FilePath = Storage::putFileAs(
			"/uploads/{$now->year}/{$now->month}",
			$file,
			$FileName
		);

		return response()->json( [ 'message' => 'Image saved Successfully', ] );
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