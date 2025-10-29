<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * @return View
     */
    public function index(): View {
        return view('auth.login');
    }

    /**
     * @return View
     */
    public function register(): View {
        return view('auth.register');
    }

    /**
     * Register new user 
     * 
     * @return RedirectResponse
     */
    public function postRegister(Request $request): RedirectResponse {
        // Validate request
        $request->validate(
            [
                'name' => 'required|min:3|max:100',
                'email' => 'required|email|unique:users',
                'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()]
            ]
        );

        // Create new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return redirect()->route('login')->with('success', 'Success register new user.');
    }

    /**
     * Login using registered account
     * 
     * @return RedirectResponse
     */
    public function postLogin(Request $request): RedirectResponse {
        // Validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        // Takes user credentials
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)){
            // refresh session ID
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return redirect()->route('login')->with('error', 'Invalid email or password');
    }

    /**
     * Logout user and clear session
     * 
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse {
        Session::flush();
        Auth::logout();

        return redirect()->route('login');
    }
}