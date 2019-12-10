<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class EmailController extends Controller
{
    public function rules()
    {
        $rules = [
            'tieude'  => [ 'required' ],
            'noidung' => [ 'required' ],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'tieude.required'  => 'Tiêu đề là trường bắt buộc',
            'noidung.required' => 'Nội dung là trường bắt buộc',
        ];
    }

    public function counting()
    {
        $user  = Auth::user();
        $draft = Email::where(
            [
                [ 'status', 0 ],
                [ 'nguoigui', $user->email ]
            ])->count();

        $inbox = Email::where(
            [
                [ 'status', 1 ],
                [ 'nguoigui', '<>', $user->email ]
            ])
            ->whereHas('receive', function ( $query ) use ( $user ) {
                $query->where([
                    [ 'status', '<>', -1 ],
                    [ 'nguoinhan', $user->email ]
                ]);
            })->count();

        $trash = Email::where('status', 1)
            ->whereHas('receive', function ( $query ) use ( $user ) {
                $query->where([
                    [ 'status', -1 ],
                    [ 'nguoinhan', $user->email ]
                ]);
            })->count();

        $outbox = Email::where(
            [
                [ 'status', 1 ],
                [ 'nguoigui', $user->email ]
            ])
            ->whereHas('receive', function ( $query ) use ( $user ) {
                $query->where([
                    [ 'status', '<>', -1 ],
                    [ 'nguoinhan', $user->email ]
                ]);
            })->count();

        return [
            'inbox'  => $inbox,
            'outbox' => $outbox,
            'draft'  => $draft,
            'trash'  => $trash,
        ];
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function index( Request $request )
    {
        $permission = user_can('all');

        if ( array_sum($permission) == 0 || !user_can('view') ) {
            abort(404);
        }

        $mailbox = $request->input('mailbox', 'inbox'); // send, inbox, outbox, trash, draft, show, edit

        return view(name_blade('Backend.email.app'), [
            'mailbox'    => $mailbox,
            'permission' => $permission,
            'counting'   => $this->counting(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return string
     */
    public function show( $id )
    {
        if ( !user_can('view') ) {
            abort(404);
        }

        $data  = [];
        $email = Email::findOrFail($id)->toArray();
        if ( !empty($email['files']) ) {
            foreach ( $email['files'] as $file ) {
                $data[] = Posts::where('id', $id)
                    ->get()
                    ->first()
                    ->toArray();
            }
        }
        $email['files'] = $data;

        return view(
            name_blade('Backend.email.app'),
            [
                'email'    => $email,
                'mailbox'  => 'show',
                'reply'    => route('email.edit', [
                    $email['id'],
                    'type' => 'reply'
                ]),
                'forward'  => route('email.edit', [
                    $email['id'],
                    'type' => 'forward'
                ]),
                'counting' => $this->counting(),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Factory|View
     */
    public function edit( Request $request, $id )
    {
        if ( !user_can('edit') ) {
            abort(404);
        }

        $data  = [];
        $type  = $request->get('type', '');
        $email = Email::findOrFail($id)->toArray();

        if ( !empty($email['files']) ) {
            foreach ( $email['files'] as $file ) {
                $data[] = Posts::where('id', $id)
                    ->get()
                    ->first()
                    ->toArray();
            }
        }
        $email['files'] = $data;

        if ( $type == 'reply' ) {
            $email['receive'] = [
                [ 'nguoinhan' => $email['nguoigui'] ]
            ];
            $email['id']      = '';
            $email['tieude']  = '';
            $email['noidung'] = '';
            $email['files']   = [];
        } elseif ( $type == 'forward' ) {
            $email['receive'] = [];
            $email['id']      = '';
        }

        return view(
            name_blade('Backend.email.app'),
            [
                'email'    => $email,
                'mailbox'  => 'edit',
                'counting' => $this->counting(),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function create( Request $request )
    {
        $user    = Auth::user();
        $limit   = $request->input('length');
        $start   = $request->input('start');
        $search  = $request->input('search.value');
        $mailbox = $request->input('mailbox', 'inbox');
        $args    = [
            [ 'id', '>', 0 ],
        ];
        switch ( $mailbox ) {
            case 'inbox':
                $args[] = [ 'status', 1 ];
                $args[] = [ 'nguoigui', '<>', $user->email ];
                break;
            case 'outbox':
                $args[] = [ 'status', 1 ];
                $args[] = [ 'nguoigui', $user->email ];
                break;
            case 'draft':
                $args[] = [ 'status', 0 ];
                $args[] = [ 'nguoigui', $user->email ];
                break;
        }
        $condition = Email::where($args);

        if ( $mailbox == 'inbox' ) {
            $condition->whereHas('receive',
                function ( $query ) use ( $user ) {
                    $query->where([
                        [ 'status', '<>', -1 ],
                        [ 'nguoinhan', $user->email ]
                    ]);
                })
                ->with([
                    'receive' => function ( $query ) use ( $user ) {
                        $query->where([
                            [ 'status', '<>', -1 ],
                            [ 'nguoinhan', $user->email ]
                        ]);
                    }
                ]);
        } elseif ( $mailbox == 'trash' ) {
            $condition->whereHas('receive',
                function ( $query ) use ( $user ) {
                    $query->where([
                        [ 'status', -1 ],
                        [ 'nguoinhan', $user->email ]
                    ]);
                })
                ->with([
                    'receive' => function ( $query ) use ( $user ) {
                        $query->where([
                            [ 'status', -1 ],
                            [ 'nguoinhan', $user->email ]
                        ]);
                    }
                ]);
        } elseif ( $mailbox == 'outbox' ) {
            $condition->whereHas('receive',
                function ( $query ) use ( $user ) {
                    $query->where([
                        [ 'status', '<>', -1 ],
                        [ 'nguoinhan', $user->email ]
                    ]);
                }
            );
        }

        $totalData = $condition->count();

        if ( empty($search) ) {
            $totalFiltered = $totalData;
            $email         = $condition
                ->offset($start)
                ->limit($limit)
                ->latest()
                ->get()
                ->toArray();
        } else {
            $totalFiltered = $condition
                ->where(
                    function ( $query ) use ( $search ) {
                        $query->where('tieude', 'LIKE', "%{$search}%")
                            ->orWhere('noidung', 'LIKE', "%{$search}%");
                    }
                )->count();
            $email         = $condition
                ->where(
                    function ( $query ) use ( $search ) {
                        $query->where('tieude', 'LIKE', "%{$search}%")
                            ->orWhere('noidung', 'LIKE', "%{$search}%");
                    }
                )
                ->offset($start)
                ->limit($limit)
                ->latest()
                ->get()
                ->toArray();
        }

        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $email,
        ];

        return response()->json($json_data);
    }

    public function fill_data( Request $request )
    {
        $user             = Auth::user();
        $data             = $request->all();
        $data['nguoigui'] = $user->email;

        return $data;
    }

    public function saveEmail( Request $request, $email_id )
    {
        $meta      = [];
        $user      = Auth::user();
        $send_type = $request->input('send_type');
        $nguoinhan = $request->input('nguoinhan');

        if ( $send_type == 0 ) {
            $meta = explode(',', trim($nguoinhan));
        } else {
            $donvi = Donvi::where(
                [
                    'khoi'   => $send_type,
                    'status' => 1,
                ])
                ->WhereHas('users')
                ->get()
                ->toArray();

            if ( !empty($donvi) ) {
                foreach ( $donvi as $item ) {
                    if ( !empty($item['users']) ) {
                        foreach ( $item['users'] as $user ) {
                            $meta[] = $user['email'];
                        }
                    }
                }
            }
        }

        $meta[] = $user->email;

        foreach ( $meta as $value ) {
            EmailReceive::updateOrCreate(
                [
                    'email_id'  => $email_id,
                    'nguoinhan' => $value
                ],
                [
                    'status' => 0
                ]
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store( Request $request )
    {
        if ( !user_can('add') ) {
            return response()->json([
                'status'  => 400,
                'message' => [ 'Bạn không được cấp quyền thêm dữ liệu' ],
            ]);
        }

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ( $validator->passes() ) {

            $email = new Email();

            $email->fill(
                $this->fill_data($request)
            );

            if ( $email->save() ) {

                $email_id = $email->getAttributeValue('id');
                $status   = $request->input('status');
                $message  = $status == 1 ? 'Gửi thư' : 'Lưu thư nháp';

                $this->saveEmail($request, $email_id);

                return response()->json([
                    'status'  => 200,
                    'message' => $message . ' thành công.',
                    'data'    => $this->counting(),
                ]);

            }

            return response()->json([
                'status'  => 400,
                'message' => [ $message . ' không thành công.' ],
            ]);
        }

        return response()->json([
            'status'  => 400,
            'message' => $validator->errors()->all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function update( Request $request, $id )
    {
        if ( $request->has('delete') ) {
            $restore = false;
            if ( $request->has('restore') ) {
                $restore = true;
            }
            return $this->softDelete($id, $restore);
        }

        if ( !user_can('edit') ) {
            return response()->json([
                'status'  => 400,
                'message' => [ 'Bạn không được cấp quyền sửa dữ liệu.' ],
                'data'    => [],
            ]);
        }

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ( $validator->passes() ) {

            $user    = Auth::user();
            $status  = $request->input('status');
            $message = $status == 1 ? 'Cập nhật thư' : 'Cập nhật thư nháp';

            $email = Email::find($id);

            $email->fill(
                $request->all()
            );

            if ( $email->save() ) {

                if ( $request->has('read') ) {
                    EmailReceive::where([
                        'email_id'  => $id,
                        'nguoinhan' => $user->email
                    ])->update([
                        'status' => 1
                    ]);
                } else {
                    $this->saveEmail($request, $id);
                }

                return response()->json([
                    'status'  => 200,
                    'message' => $message . ' thành công.',
                    'data'    => $this->counting(),
                ]);

            }

            return response()->json([
                'status'  => 400,
                'message' => [ $message . ' không thành công.' ],
            ]);
        }

        return response()->json([
            'status'  => 400,
            'message' => $validator->errors()->all(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  boolean  $restore
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function softDelete( $ids, $restore = false )
    {
        if ( !user_can('delete') ) {
            return response()->json([
                'status'  => 'warning',
                'title'   => 'Cảnh báo!',
                'message' => 'Bạn không được cấp quyền xóa dữ liệu!',
            ]);
        }

        $count  = 0;
        $user   = Auth::user();
        $status = $restore ? 1 : -1;
        $text   = $restore ? 'Khôi phục' : 'Xóa';

        if ( !is_numeric($ids) && !is_array($ids) && is_string($ids) ) {
            $ids = explode(',', $ids);
        }

        $ids = is_array($ids) ? $ids : func_get_args();

        foreach ( Email::whereIn('id', $ids)->get() as $model ) {
            EmailReceive::where([
                'email_id'  => $model->id,
                'nguoinhan' => $user->email
            ])->update([
                'status' => $status
            ]);

            $count++;
        }

        if ( $count > 0 ) {
            return response()->json([
                'status'  => "success",
                'title'   => "Đã {$text}!",
                'message' => "Đã {$text} {$count} thư!",
                'data'    => $this->counting(),
            ]);
        }

        return response()->json([
            'status'  => "error",
            'title'   => "Lỗi!",
            'message' => "{$text} thư không thành công!",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function destroy( Request $request, $id )
    {
        if ( !user_can('delete') ) {
            return response()->json([
                'status'  => 'warning',
                'title'   => 'Cảnh báo!',
                'message' => 'Bạn không được cấp quyền xóa dữ liệu!',
            ]);
        }

        $count = Email::destroy($id);

        if ( $count > 0 ) {
            return response()->json([
                'status'  => 'success',
                'title'   => 'Đã xóa!',
                'message' => 'Đã xóa ' . $count . ' email!',
                'data'    => $this->counting(),
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'title'   => 'Lỗi!',
            'message' => 'Xóa email không thành công!',
        ]);
    }
}
