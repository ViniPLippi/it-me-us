<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'cpf' => ['required', 'string', 'min:11', 'max:11', 'unique:users'],
            'tipo' => ['required', 'string', 'min:1', 'max:2'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data){
        return User::create([
            'cpf' => $data['cpf'],
            'tipo' => $data['tipo'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function index(){
        $usuarios = User::all();
        return view('usuarios', compact('usuarios'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(isset($user)){
            $user->name = $request->input('name');
            $user->cpf = $request->input('cpf');
            $user->tipo = $request->input('tipo');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password_confirmation'));
            $user->save();
        }
        return redirect('/usuarios');
    }

    public function updateSenha(Request $request, $id)
    {
        $request->validate([
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation'  => 'required|same:password|string|min:8'
        ]);

        $user = User::find($id);
        if(isset($user)){
            $user->password = Hash::make($request->input('password_confirmation'));
            $user->save();
        }
        return redirect('/tickets');
    }


    public function edit($id)
    {
        $user = User::find($id);
        if(isset($user)){
            return view('auth/registerupdate',compact('user'));
        }
        return redirect('/usuarios');
    }

    public function trocarSenha($id)
    {
        $user = User::find($id);
        if(isset($user)){
            return view('auth/registertrocarsenha',compact('user'));
        }
        return redirect('/usuarios');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if(isset($user)){
            $user->delete();
        }
        return redirect('/usuarios');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cpf'                   => 'required|string|min:11|max:11|unique:users',
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation'  => 'required|same:password|string|min:8'
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->cpf = $request->input('cpf');
        $user->tipo = $request->input('tipo');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password_confirmation'));
        $user->save();
        return redirect('/usuarios');
    }
}
