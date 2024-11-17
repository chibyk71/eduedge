<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // get the current school/tenant id to authenticate the user against
        $currentSchool = tenant('id');

        /* 
            now we get the authentication data from the request and authenticate the user
            then check is the user belongs to the current school/tenant
            if not, logout the user and redirect him to the login page with an error message
            if yes, continue with the normal authentication process
        */

        // get the authentication data from the request
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // authenticate the user
        if (Auth::attempt($credentials, $remember)) {

            // check if the user belongs to the current school/tenant
            $isInSchool = Auth::user()->schools()->where('schools.id', '=', $currentSchool)->exists();
            if ($isInSchool) {
                // continue with the normal authentication process
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard', absolute: false));
            }else {
                // the user does not belong to the school and so cant access the dashbord,
                // they are loged out and redirected to login again with an error message
                Auth::logout();
                $request->session()->invalidate();
                redirect()->route('login')->with('error', 'Invalid credentials.');
            }
        } else {
            // authentication failed
            return redirect()->route('login')->with('error', 'Invalid credentials.');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
