<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    { 
        if (auth()->check()) {
            
            return redirect("/dashboard");
        }

        return view('frontend.auth.login');
    }
}
