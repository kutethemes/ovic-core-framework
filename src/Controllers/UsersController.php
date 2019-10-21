<?php
namespace Ovic\Framework;

use App\Http\Controllers\Controller;

class UsersController extends Controller
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		return view( ovic_blade( 'Backend.users.app' ) );
	}
}