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

		if ( !empty( $request['_form'] ) ) {
			$args = [
				[ "post_type", "=", "attachment" ],
				[ "status", "=", "publish" ],
			];

			if ( !empty( $request['_form']['s'] ) ) {
				$args[] = [ "title", "like", "%{$request['_form']['s']}%" ];
			}

			if ( !empty( $request['_form']['dir'] ) ) {
				$args[] = [ "name", "like", "%{$request['_form']['dir']}%" ];
			}

			$attachments = \Ovic\Framework\Post::get_posts( $args );

			foreach ( $attachments as $key => $attachment ) {
				$mimetype  = $attachment['meta']['_attachment_metadata']['mimetype'];
				$extension = $attachment['meta']['_attachment_metadata']['extension'];

				switch ( $request['_form']['sort'] ) {
					case 'im':
						if ( !strstr( $mimetype, "image/" ) ) {
							unset( $attachments[$key] );
						}
						break;

					case 'vi':
						if ( !strstr( $mimetype, "video/" ) ) {
							unset( $attachments[$key] );
						}
						break;

					case 'au':
						if ( !strstr( $mimetype, "audio/" ) ) {
							unset( $attachments[$key] );
						}
						break;

					case 'doc':
						$ext_allow = [ 'doc', 'docx', 'xls', 'xlsx', 'pdf' ];
						if ( !in_array( $extension, $ext_allow ) ) {
							unset( $attachments[$key] );
						}
						break;

					case 'ar':
						$ext_allow = [ 'rar', 'zip' ];
						if ( !in_array( $extension, $ext_allow ) ) {
							unset( $attachments[$key] );
						}
						break;
				}
			}

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

		$FileName = str_replace( ".{$extension}", "-{$now->getTimestamp()}.{$extension}", $OriginalName );

		$FilePath = Storage::putFileAs(
			"{$this->folder}{$now->year}/{$now->month}",
			$file,
			$FileName
		);

		$created = \Ovic\Framework\Post::add_post(
			[
				'title'     => $FileName,
				'name'      => "{$now->year}/{$now->month}/{$FileName}",
				'post_type' => 'attachment',
				'meta'      => [
					'_attachment_metadata' => [
						'alt'       => '',
						'size'      => size_format( $FileSize ),
						'mimetype'  => $MimeType,
						'extension' => $extension,
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

		$attachment = \Ovic\Framework\Post::get_posts(
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

		$path = Post::find( $request->id )->toArray()['name'];
		$path = str_replace( '//', '/', "{$this->folder}{$path}" );

		Storage::delete( $path );

		$removed = Post::remove_post( $request->id );

		return response()->json( $removed, $removed['code'] );
	}
}