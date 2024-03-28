<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
    }

    public function index()
    {
        return view('users');
    }

    public function indexJSON(){
        $users = DB::table('users')->get();
        return Datatables::of($users)
            ->addColumn('action', function ($row) {
                $html = '<button class="btn btn-sm btn-primary" onclick="editar(' . $row->id . ')">Editar</button>' . ' ';
                $html .= '<button class="btn btn-sm btn-danger" onclick="excluir(' . $row->id . ')">Excluir</button>';
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(array $data)
    {
        return User::create([
            'cpf' => $data['cpf'],
            'tipo' => $data['tipo'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuario = new User();
        $usuario->name = $request->input('nome');
        $usuario->cpf  = $request->input('cpf');
        $usuario->tipo = $request->input('tipo');
        $usuario->email = $request->input('email');
        $usuario->password = Hash::make($request->input('password'));

        info($usuario);
        $usuario->save();
        return json_encode($usuario);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        if(isset($user)){
            return json_encode($user);
        }
        return response('Usuário não encontrado!',400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        info('---------');
        info($user);
        info('---------');

        $name = $request->input('nome');
        $cpf = $request->input('cpf');
        $email = $request->input('email');
        $tipo = $request->input('tipo');
        $password = $request->input('password');

        if(isset($user)){

            $user->name  = $name;
            $user->cpf   = $cpf;
            $user->tipo  = $tipo;
            $user->email = $email;
            $user->password = $password;
            
            $user->save();
            return json_encode($user);
        }

        return response('Usuário não encontrado!',400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        info($user);
        if(isset($user)){
            $user->delete();
        }
    }
}
