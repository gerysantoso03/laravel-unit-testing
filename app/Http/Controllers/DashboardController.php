<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard view
     * 
     * @return View
     */
    public function index(): View {

        if(Auth::check()){
            // Get logged user
            $user = Auth::user();

            return view('welcome', compact('user'));
        }
        

        return view('auth.login');
    }
}
