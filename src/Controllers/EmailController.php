<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        return view(
            name_blade('Backend.email.app')
        );
    }
}