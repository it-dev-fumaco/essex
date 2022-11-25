<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use App\LdapClasses\adLDAP;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
    }

    public function userLogout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }

    public function showLoginForm(){
        return view('home');
    }

    public function userLogin(Request $request){
        $this->validate($request, [
            'user_id' => 'required',
            'password' => 'required'
        ]);

        $success = '';
        if ($request->login_as == 'ldap-login') {
            $is_user = DB::table('users')->where('user_id', $request->user_id)->first();
            if ($is_user) {
                if ($is_user->email) {
                    $adldap = new adLDAP();
                    $authUser = $adldap->user()->authenticate(explode('@', $is_user->email)[0], $request->password);
                    if($authUser == true){
                         if(Auth::loginUsingId($is_user->id)){
                            $success = 1;
                        } 
                    }
                }
            }
        } else {
            if (Auth::attempt(['user_id' => $request->user_id,'password' => $request->password], $request->remember)) {
                $success = 1;
            }
        }

        return $success;
    }
}