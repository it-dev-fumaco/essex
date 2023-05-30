<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use App\LdapClasses\adLDAP;
use Carbon\Carbon;

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
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'user_id' => 'required',
                'password' => 'required'
            ]);

            $success = $id = null;
            if ($request->login_as == 'ldap-login') {
                $email = $request->user_id . '@fumaco.local';
                $is_user = DB::table('users')->where('email', $email)->first();

                if ($is_user) {
                    $id = $is_user->id;
                    if ($is_user->email) {
                        $adldap = new adLDAP();
                        $authUser = $adldap->user()->authenticate(explode('@', $is_user->email)[0], $request->password);
                        if($authUser == true){
                             if(Auth::loginUsingId($is_user->id)){
                                $success = 1;
                                DB::table('users')->where('user_id', $is_user->user_id)->update(['last_login_date' => Carbon::now()->toDateTimeString()]);
                            } 
                        }
                    }
                }
            } else {
                if (Auth::attempt(['user_id' => $request->user_id,'password' => $request->password], $request->remember)) {
                    $success = 1;
                    $is_user = DB::table('users')->where('user_id', $request->user_id)->first();
                    $id = $is_user ? $is_user->id : $id;

                    DB::table('users')->where('user_id', $request->user_id)->update(['last_login_date' => Carbon::now()->toDateTimeString()]);
                }
            }

            if($success){
                try {
                    exec('cd '.ENV('BASE_PATH').' && php artisan emails:birthday --id='.$id);
                    exec('cd '.ENV('BASE_PATH').' && php artisan emails:worksary --id='.$id);
                } catch (\Throwable $th) {}
            }

            DB::commit();
            return $success;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}