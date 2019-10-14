<?php
namespace Ovic\Dashboad;

use App\Http\Controllers\Controller;

class DashboadController extends Controller
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		return view(
			ovic_blade( 'Backend.dashboard.app' )
		);
	}
}