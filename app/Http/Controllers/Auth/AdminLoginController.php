<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['adminLogout']]);
    }

    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(AdminLoginRequest $request)
    {

        if (Auth::guard('admin')->attempt(['access_id' => $request->access_id, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function adminLogout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->redirectToApplicationPath($request, '/admin/login');
    }
}
