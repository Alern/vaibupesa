<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'msisdn' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            //Check for landing page
            $user = auth()->user();
            $inputs1=$user->id;
            $loggedinuserbal=DB::table('customers')->where('user_id', '=', $inputs1)->pluck('topupamnt')->first();

            if($loggedinuserbal == 0 ){
               // return redirect()->intended('/registeredcustomers/create');
                return redirect()->intended('/registered/nokyclandingpage');
            }else{
                return redirect()->intended('/registered/landingpage');
            }

        }

        return back()->withErrors([
            'msisdn' => 'The provided credentials do not match our records.',
        ])->onlyInput('msisdn');
    }

    use AuthenticatesUsers;

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
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
