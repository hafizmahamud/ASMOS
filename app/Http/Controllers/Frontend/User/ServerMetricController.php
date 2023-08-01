<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

/**
 * Class ServerMetricController.
 */
class ServerMetricController extends Controller
{
    public function index($id)
    {
      $server_all = DB::table('monitors')->find($id);
      return view('frontend.user.servermetric', compact('server_all'));
    }

}