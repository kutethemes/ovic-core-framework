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

class ImporterController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
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
     * @return JsonResponse
     */
    public function create( Request $request )
    {
        $type   = $request->input('type', 'export');
        $target = $request->input('target', 'user');

        if ( $type == 'export' ) {
            if ( $target == 'user' ) {
                $donvi  = $request->input('donvi', '');
                $status = $request->input('status', '');

                return Excel::download(new UsersExport($donvi, $status),
                    'danh-sach-nguoi-dung.xlsx'
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

                return response()->json([
                    'status'  => 400,
                    'message' => 'import không thành công!'
                ]);
            }
        }
    }
}
