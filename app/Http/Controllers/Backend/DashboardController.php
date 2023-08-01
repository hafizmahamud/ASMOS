<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use DB;
use Auth;
use Carbon\Carbon;
use \Datetime;
/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {   
        return view('backend.dashboard');
    }
}
