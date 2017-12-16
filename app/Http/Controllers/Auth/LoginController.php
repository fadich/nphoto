<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout')->except('indexAction');
        parent::__construct($request);
    }

    public function indexAction()
    {
//        $url = env('APP_URL');
//        $redirect = "https://www.auth.royallib.pw/auth?land-to={$url}admin/#/sign-in";
        $errors = [];
        $data['email'] = '';

        if ($this->request->getMethod() === 'POST') {
            $attr['email'] = $this->request->get('email');
            $attr['password'] = $this->request->get('password');
            $success = Auth::attempt($attr);

            if ($success) {
                return $this->redirect(route('admin.index'));
            }

            $data['email'] = $attr['email'];
            $errors['password'] = 'Incorrect email or password';
        }

        return $this->render('auth.login', [
            'errors' => $errors,
            'data' => $data,
        ]);
    }

}
