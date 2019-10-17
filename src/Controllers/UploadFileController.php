<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{
	private $folder = 'uploads/';

	/**
	 * filter images through XHR Request.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function filter( Request $request )
	{
		$request = $request->toArray();
		if ( !empty( $request['data'] ) ) {
			switch ( $request['data'] ) {
				case 'search';
					$args = [
						[ "post_type", "=", "attachment" ],
						[ "status", "=", "publish" ],
					];

					if ( !empty( $request['s'] ) ) {
						$args[] = [ "title", "like", "%{$request['s']}%" ];
					}

					$attachments = \Ovic\Framework\Post::get_images( $args );

					$html = '';

					foreach ( $attachments as $attachment ) {
						$html .= view( ovic_blade( 'Backend.media.item' ), compact( 'attachment' ) )->toHtml();
					}

					return response()->json(
						[
							'status'  => 'success',
							'message' => 'Đã tìm được ' . count( $attachments ) . ' kết quả.',
							'html'    => $html,
						]
					);
					break;

				case 'file_type':
					break;
			}
		}

		return response()->json(
			[
				'status'  => 'error',
				'message' => 'Không rõ kiểu lọc.',
			], 400
		);
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
					'status'  => 'error',
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
			"{$this->folder}{$now->year}/{$now->month}",
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
						'size'      => size_format( $FileSize ),
						'mimetype'  => $MimeType,
						'extension' => $extension,
						'path'      => str_replace( $this->folder, '', $FilePath ),
					],
				],
				'user_id'   => Auth::user()->id,
				'owner_id'  => Auth::user()->id,
			]
		);

		if ( $created['code'] == 400 ) {
			Storage::delete( $FilePath );

			return response()->json(
				[
					'status'  => 'error',
					'message' => 'The File can not save.',
				], 400
			);
		}

		$attachment = \Ovic\Framework\Post::get_images(
			[
				[ 'id', '=', $created['post_id'] ],
				[ 'post_type', '=', 'attachment' ],
				[ 'status', '=', 'publish' ],
			]
		);
		$attachment = array_shift( $attachment );

		return response()->json(
			[
				'status'  => 'success',
				'message' => 'Image saved Successfully',
				'html'    => view(
					ovic_blade( 'Backend.media.item' ),
					compact( 'attachment' )
				)->toHtml(),
			]
		);
	}

	/**
	 * Remove the images from the storage.
	 *
	 * @param Request $request
	 */
	public function remove( Request $request )
	{
		if ( !$request->has( 'id' ) ) {
			return response()->json(
				[
					'status'  => 'error',
					'message' => 'The do not have ID.',
				], 400
			);
		}

		$attachment = Postmeta::get_post_meta( $request->id, '_attachment_meta' );

		Storage::delete( "{$this->folder}{$attachment['path']}" );

		$removed = Post::remove_post( $request->id );

		return response()->json( $removed, $removed['code'] );
	}
}