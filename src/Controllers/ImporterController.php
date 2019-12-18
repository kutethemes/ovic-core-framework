<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImporterController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $permission = user_can('all');

        if ( array_sum($permission) == 0 || !user_can('view') ) {
            abort(404);
        }

        return view(
            name_blade('Backend.importer.app'),
            [
                'donvi' => Donvi::getDonvi(),
                'role'  => Roles::getRoles(),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return BinaryFileResponse | JsonResponse
     */
    public function create( Request $request )
    {
        $type   = $request->input('type', 'export');
        $target = $request->input('target', 'user');

        if ( $type == 'export' ) {
            if ( $target == 'user' ) {
                $name   = $request->input('fileName', 'DS-Nguoidung');
                $donvi  = $request->input('donvi', '');
                $status = $request->input('status', '');

                ob_end_clean(); // this
                ob_start(); // and this

                return Excel::download(new UsersExport($donvi, $status),
                    "{$name}.xlsx"
                );
            }
        } else {
            if ( $target == 'user' ) {
                $validator = Validator::make($request->all(),
                    [
                        'file' => [ 'required' ]
                    ],
                    [
                        'file.required' => 'Chưa chọn file import.',
                    ]
                );

                if ( $validator->passes() ) {
                    $file  = $request->input('file');
                    $role  = $request->input('role', []);
                    $donvi = $request->input('donvi', 0);

                    Excel::import(
                        new UsersImport($role, $donvi),
                        storage_path("app/uploads/{$file}")
                    );

                    return response()->json([
                        'status'  => 200,
                        'message' => 'import thành công!'
                    ]);
                }
            }
        }

        return response()->json([
            'status'  => 400,
            'message' => 'import không thành công!'
        ]);
    }
}
