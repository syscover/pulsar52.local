<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Route to get login form
     *
     * @var string
     */
    protected $loginPath;

    /**
     * Redirect route after logout
     *
     * @var string
     */
    protected $logoutPath;

    /**
     * Here you can customize your guard, this guar has to set in auth.php config
     *
     * @var string
     */
    protected $guard;


    public function __construct()
    {
        $this->redirectTo   = route('account-' . user_lang());
        $this->loginPath    = route('login-' . user_lang());
        $this->logoutPath   = route('home-' . user_lang());
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'user'      => 'required',
            'password'  => 'required',
        ]);

        $credentials = [
            'user_301'  => $request->input('user'),
            'password'  => $request->input('password')
        ];

        if(auth('crm')->attempt($credentials, $request->has('remember')))
        {
            // check if customer is active
            if(!auth('crm')->user()->active_301)
            {
                auth('crm')->logout();

                // error user inactive
                if($request->input('responseType') == 'json')
                {
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'User inactive'
                    ]);
                }
                else
                {
                    return redirect($this->loginPath)->withErrors([
                        'message'   => 'User inactive'
                    ])->withInput();
                }
            }

            if($request->input('responseType') == 'json')
            {
                return response()->json([
                    'status' => 'success',
                    'customer' => auth('crm')->user()
                ]);
            }
            else
            {
                return redirect($this->redirectTo);
            }
        }

        // error authentication
        if($request->input('responseType') == 'json')
        {
            return response()->json([
                'status' => 'error',
                'message' => 'User or password incorrect'
            ]);
        }
        else
        {
            return redirect($this->loginPath)->withErrors([
                'message' => 'User or password incorrect'
            ])->withInput();
        }
    }

    /**
     * Logout user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth('crm')->logout();

        return redirect($this->logoutPath);
    }
}
