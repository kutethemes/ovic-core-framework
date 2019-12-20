<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImagesController extends Controller
{
    public function getImage( $year, $month, $filename )
    {
        $path = storage_path() . "/app/uploads/{$year}/{$month}/{$filename}";

        if ( !File::exists($path) ) {
            return response($path, 400);
        }

        $content = File::get($path);
        $type    = File::mimeType($path);

        return response()->file($path, [ "Content-Type", $type ]);
    }
}
