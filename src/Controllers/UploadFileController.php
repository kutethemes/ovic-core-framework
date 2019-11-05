<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadFileController extends Controller
{
    private $folder = 'uploads/';

    public $limit       = 18;
    public $offset      = 0;
    public $attachments = [];
    public $directories = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->init();
    }

    public function get_attachments( $args )
    {
        $limit  = 18;
        $offset = 0;
        if ( isset($args['limit']) ) {
            $limit = $args['limit'];
            unset($args['limit']);
        }
        if ( isset($args['offset']) ) {
            $offset = $args['offset'];
            unset($args['offset']);
        }
        $posts = Posts::where($args)
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();

        return $posts;
    }

    public function add_attachments( $request )
    {
        $name = !empty($request['name']) ? $request['name'] : Str::slug($request['title']);

        $post = new Posts();

        $post->name      = $name;
        $post->title     = $request['title'];
        $post->post_type = $request['post_type'];
        $post->content   = !empty($request['content']) ? $request['content'] : '';
        $post->user_id   = !empty($request['user_id']) ? $request['user_id'] : 0;
        $post->owner_id  = !empty($request['owner_id']) ? $request['owner_id'] : 0;
        $post->status    = !empty($request['status']) ? $request['status'] : 'publish';

        $post->save();

        $post_id = $post->getAttributeValue('id');

        if ( !empty($request['meta']) ) {
            foreach ( $request['meta'] as $meta_key => $meta_value ) {
                $meta             = new Postmeta();
                $meta->post_id    = $post_id;
                $meta->meta_key   = maybe_serialize($meta_key);
                $meta->meta_value = maybe_serialize($meta_value);

                $meta->save();
            }
        }

        return $post_id;
    }

    public function init()
    {
        $this->attachments = $this->get_attachments(
            [
                [ 'post_type', '=', 'attachment' ],
                [ 'status', '=', 'publish' ],
                'limit'  => $this->limit,
                'offset' => $this->offset,
            ]
        );
        $this->directories = $this->createDirectories($this->attachments);
    }

    public function createDirectories( $attachments )
    {
        $folder      = [];
        $year        = '';
        $directories = [];
        if ( !empty($attachments) ) {
            foreach ( $this->attachments as $attachment ) {
                $dir_year = explode('/', $attachment['name']);
                $dir_year = array_shift($dir_year);
                $dir      = str_replace($attachment['title'], '', $attachment['name']);

                $directories[$dir_year][] = $dir;
            }

            asort($directories);

            foreach ( $directories as $year => $month ) {
                $month = array_unique(array_values($month));
                $data  = [
                    "text"   => "Năm {$year}",
                    "a_attr" => [
                        "class"    => "dir-filter",
                        "data-dir" => $year,
                    ]
                ];
                if ( !empty($month) ) {
                    foreach ( $month as $mon ) {
                        $data['children'][] = [
                            "text"   => "Tháng ".str_replace([ $year, '/' ], [ '', '' ], $mon),
                            "a_attr" => [
                                "class"    => "dir-filter",
                                "data-dir" => $mon,
                            ]
                        ];
                    }
                }
                $folder[] = $data;
            }
        }

        return $folder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(ovic_blade('Backend.media.app'))->with([
            'attachments' => $this->attachments,
            'limit'       => $this->limit,
            'offset'      => $this->offset,
            'directories' => json_encode($this->directories)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Show the modal data for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function modal( Request $request )
    {
        $content = '';
        $dir     = '';
        if ( !empty($this->attachments) ) {
            foreach ( $this->attachments as $attachment ) {
                $content .= view(ovic_blade('Backend.media.image'), compact([ 'attachment' ]))->toHtml();
            }
        }
        $this->init();
        return response()->json([
            'content'     => $content,
            'directories' => json_encode($this->directories)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter( Request $request )
    {
        $request = $request->toArray();

        if ( !empty($request['_form']) ) {
            $args = [
                [ 'post_type', '=', 'attachment' ],
                [ 'status', '=', 'publish' ],
                'limit'  => $request['_form']['limit'],
                'offset' => $request['_form']['offset'],
            ];

            if ( !empty($request['_form']['s']) ) {
                $args[] = [ "title", "like", "%{$request['_form']['s']}%" ];
            }

            if ( !empty($request['_form']['dir']) ) {
                $args[] = [ "name", "like", "%{$request['_form']['dir']}%" ];
            }

            $attachments = $this->get_attachments($args);

            foreach ( $attachments as $key => $attachment ) {
                $mimetype  = $attachment['meta']['_attachment_metadata']['mimetype'];
                $extension = $attachment['meta']['_attachment_metadata']['extension'];

                switch ( $request['_form']['sort'] ) {
                    case 'im':
                        if ( !strstr($mimetype, "image/") ) {
                            unset($attachments[$key]);
                        }
                        break;

                    case 'vi':
                        if ( !strstr($mimetype, "video/") ) {
                            unset($attachments[$key]);
                        }
                        break;

                    case 'au':
                        if ( !strstr($mimetype, "audio/") ) {
                            unset($attachments[$key]);
                        }
                        break;

                    case 'doc':
                        $ext_allow = [ 'doc', 'docx', 'xls', 'xlsx', 'pdf' ];
                        if ( !in_array($extension, $ext_allow) ) {
                            unset($attachments[$key]);
                        }
                        break;

                    case 'ar':
                        $ext_allow = [ 'rar', 'zip' ];
                        if ( !in_array($extension, $ext_allow) ) {
                            unset($attachments[$key]);
                        }
                        break;
                }
            }

            $html = '';

            foreach ( $attachments as $attachment ) {
                $html .= view(ovic_blade('Backend.media.image'), compact('attachment'))->toHtml();
            }

            $count  = count($attachments);
            $status = $count > 0 ? 'success' : 'info';

            return response()->json(
                [
                    'status'  => $status,
                    'message' => 'Đã tìm được '.$count.' kết quả.',
                    'html'    => $html,
                    'count'   => $count,
                ]
            );
        }

        return response()->json(
            [
                'status'  => 'error',
                'message' => 'Không rõ kiểu lọc.',
                'html'    => '',
            ], 400
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        if ( !$request->hasFile('file') ) {
            return response()->json(
                [
                    'status'  => 'error',
                    'message' => 'The File do not exits.',
                    'html'    => '',
                ], 400
            );
        }

        $now  = now();
        $file = $request->file('file');

        $MimeType     = $file->getClientMimeType();
        $extension    = $file->getClientOriginalExtension();
        $FileSize     = $file->getSize();
        $OriginalName = $file->getClientOriginalName();

        $FileName = str_replace(".{$extension}", "-{$now->getTimestamp()}.{$extension}", $OriginalName);

        $FilePath = Storage::putFileAs(
            "{$this->folder}{$now->year}/{$now->month}",
            $file,
            $FileName
        );

        $post_id = $this->add_attachments(
            [
                'title'     => $FileName,
                'name'      => "{$now->year}/{$now->month}/{$FileName}",
                'post_type' => 'attachment',
                'meta'      => [
                    '_attachment_metadata' => [
                        'alt'       => '',
                        'size'      => size_format($FileSize),
                        'mimetype'  => $MimeType,
                        'extension' => $extension,
                    ],
                ],
                'user_id'   => Auth::user()->id,
                'owner_id'  => Auth::user()->id,
            ]
        );

        if ( $post_id > 0 ) {

            $this->init();

            return response()->json(
                [
                    'status'      => 'success',
                    'message'     => 'Lưu file thành công.',
                    'html'        => $this->show($post_id)->toHtml(),
                    'directories' => json_encode($this->directories)
                ]
            );
        } else {

            return response()->json(
                [
                    'status'      => 'error',
                    'message'     => 'Lỗi không lưu được file.',
                    'html'        => '',
                    'directories' => []
                ]
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $attachment = $this->get_attachments(
            [
                [ 'id', '=', $id ],
                [ 'post_type', '=', 'attachment' ],
                [ 'status', '=', 'publish' ],
            ]
        );

        $attachment = array_shift($attachment);

        return view(ovic_blade('Backend.media.image'), compact('attachment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $ids
     *
     * @return \Illuminate\Http\Response
     */
    public function remove( Request $request )
    {
        $deleted = [];
        $ids     = $request->input('ids');
        $ids     = ( strpos($ids, ',') === false ) ? (array) $ids : explode(',', $ids);

        foreach ( $ids as $id ) {
            $del_id = $this->destroy($request, $id, false);
            if ( $del_id != 0 ) {
                $deleted[] = $del_id;
            }
        }

        if ( !empty($deleted) ) {
            $this->init();
            $response = [
                'status'      => 200,
                'ids'         => $deleted,
                'message'     => 'Xóa file thành công.',
                'directories' => json_encode($this->directories)
            ];
        } else {
            $response = [
                'status'  => 400,
                'message' => 'Xóa file không thành công.',
            ];
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id, $response = true )
    {
        $rules    = [
            'id' => [ 'exists:posts,id,'.$id ],
        ];
        $messages = [
            'id' => 'File không tồn tại'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ( $validator->passes() ) {
            $path = Posts::where('id', $id)->value('name');
            $path = str_replace('//', '/', "{$this->folder}{$path}");

            Storage::delete($path);

            Postmeta::where('post_id', $id)->delete();
            Posts::destroy($id);

            if ( $response ) {
                $this->init();
                return response()->json([
                    'status'      => 200,
                    'message'     => 'Xóa file thành công.',
                    'directories' => json_encode($this->directories),
                ]);
            } else {
                return $id;
            }
        }
        if ( $response ) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->all(),
            ]);
        } else {
            return 0;
        }
    }
}
