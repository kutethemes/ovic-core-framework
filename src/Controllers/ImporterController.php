<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
                'donvi' => Donvi::getDonvi()
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
                $donvi = $request->input('donvi', '');

                return Excel::download(new UsersExport($donvi),
                    'danh-sach-nguoi-dung.xlsx'
                );
            }
        } else {

        }
    }
}
