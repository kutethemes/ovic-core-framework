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
            'tieude'  => [ 'required', 'string' ],
            'noidung' => [ 'required', 'string' ],
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
        $user    = Auth::user();
        $email   = Email::where('nguoigui', $user->email)->get()->collect();
        $receive = EmailReceive::where('nguoinhan', $user->email)->get()->collect();

        return [
            'inbox'  => $receive->where('status', '<>', -1)->count(),
            'outbox' => $email->where('status', 1)->count(),
            'draft'  => $email->where('status', 0)->count(),
            'trash'  => $email->where('status', -1)->count(),
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

        $mailbox = 'inbox'; // send, inbox, outbox, trash, draft, show, edit

        if ( $request->has('mailbox') ) {
            $mailbox = $request->input('mailbox');
        }

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

        $email = Email::findOrFail($id);

        return view(
            name_blade('Backend.email.app'),
            [
                'email'    => $email,
                'mailbox'  => 'show',
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
    public function edit( $id )
    {
        if ( !user_can('edit') ) {
            abort(404);
        }

        $email = Email::findOrFail($id);

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
        $mailbox = $request->input('mailbox');
        $args    = [
            [ 'id', '>', 0 ],
        ];
        switch ( $mailbox ) {
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
            $condition->whereHas('receive', function ( $query ) use ( $user ) {
                $query->where([
                    [ 'status', 1 ],
                    [ 'nguoinhan', $user->email ]
                ]);
            });
        } elseif ( $mailbox == 'trash' ) {
            $condition->whereHas('receive', function ( $query ) use ( $user ) {
                $query->where([
                    [ 'status', -1 ],
                    [ 'nguoinhan', $user->email ]
                ]);
            });
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

                $meta      = [];
                $email_id  = $email->getAttributeValue('id');
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

                return response()->json([
                    'status'  => 200,
                    'message' => 'Gửi thư thành công.',
                    'data'    => $this->counting(),
                ]);

            }

            return response()->json([
                'status'  => 400,
                'message' => [ 'Gửi thư không thành công.' ],
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
        if ( !user_can('edit') ) {
            return response()->json([
                'status'  => 400,
                'message' => [ 'Bạn không được cấp quyền sửa dữ liệu.' ],
                'data'    => [],
            ]);
        }
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ( $validator->passes() ) {

            $email = Email::find($id);

            $email->fill(
                $request->all()
            );

            if ( $email->save() ) {

                if ( $request->has('read') && $request->has('receive.0.nguoinhan') ) {
                    EmailReceive::where(
                        [
                            'email_id'  => $id,
                            'nguoinhan' => $request->input('receive.0.nguoinhan')
                        ]
                    )->update(
                        [
                            'status' => 1
                        ]
                    );
                }

                return response()->json([
                    'status'  => 200,
                    'message' => 'Cập nhật thư thành công.',
                ]);

            }

            return response()->json([
                'status'  => 400,
                'message' => [ 'Cập nhật thư không thành công.' ],
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
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function destroy( $id )
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