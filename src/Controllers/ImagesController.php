<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ImagesController extends Controller
{
    public function index( $year, $month, $filename )
    {
        $path = storage_path("app/uploads/{$year}/{$month}/{$filename}");

        if ( !File::exists($path) ) {
            return response($path, 400);
        }

        ob_end_clean(); // this
        ob_start();     // and this

        return response()->file($path);
    }
}
