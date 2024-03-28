<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username() { return 'cpf'; }

    public function showLoginForm()
    {
        return view('auth/loginpadrao');
    }

    public function authenticate(Request $request,$cpf,$password)
    {
        info("Passou aqui...13");
        $request->merge(['cpf' => $cpf]);
        $request->merge(['password' => $password]);
        try{
            $credentials = $request->validate([
                'cpf' => 'required|cpf',
                'password' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
        }

        info("Passou aqui...14");

        if (Auth::attempt($credentials)) {
            info("Passou aqui...15");
            $request->session()->regenerate();
            info("Passou aqui...16");
            return redirect()->intended('home');
        }

        return back()->withErrors([
            'cpf' => 'Usuário não cadastrado em nosso sistema.',
        ]);
    }
}
