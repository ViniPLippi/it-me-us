<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\User;
use App\Models\Turma;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class TurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('turmas');
    }

    public function indexJSON($id)
    {
        $turmas = DB::table('turmas')
            ->select('turmas.*')
            ->where('tur_esc_id', '=', $id)
            ->orderBy('id')
            ->get();
        return Datatables::of($turmas)
            ->addColumn('action', function ($row) {
                $html = '<button type="button" class="btn btn-sm btn-primary mr-2" onclick="editar(' . $row->id . ')">Editar</button>';
                $html .= '<button type="button" class="btn btn-sm btn-danger mr-2" onclick="excluir(' . $row->id . ')">Excluir</button>';
                $html .= '<button type="button" class="btn btn-sm btn-success mr-2" onclick="carregarAlunos(' . $row->id . ')">Alunos</button>';
                $html .= '<button type="button" class="btn btn-sm btn-warning" onclick="carregarTestes(' . $row->id . ')">Testes</button>';
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function indexUser($cpf){
        $user = User::where("cpf","=",$cpf)->get();
        if(isset($user)){
            return $user->toJson();
        }
        return response('Professor(a) não encontrado(a)!',400);
    }

    public function indexAluno($rge){
        $aluno = DB::table('alunos')
        ->join('estados', 'alunos.alu_est_id', '=', 'estados.id')
        ->select('alunos.*', 'estados.est_sigla')
        ->where("alu_rge","=",$rge)
        ->get();
        if(isset($aluno)){
            return $aluno->toJson();
        }
        return response('Aluno(a) não encontrado(a)!',400);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ano = $request->input('ano');
        $serie = $request->input('serie');
        $idesc = $request->input('esc_id');
        $nome = "required|max:20|unique:turmas,tur_nome,NULL,id,tur_ano,{$ano},tur_serie,{$serie},tur_esc_id,{$idesc}";
        
        $regras = [
            'nome' => $nome,
            'ano' => "required|numeric",
            'serie' => "required|numeric",
            'professor_id' =>"required",
        ];

        $mensagens = [
            'nome.required' => 'O campo Nome não pode estar em branco.',
            'ano.required' =>'O campo Ano Calendário não pode estar em branco.',
            'serie.required' => 'O campo Ano não pode estar em branco.',
            'professor_id.required' => 'O campo CPF do Professor não pode estar em branco.',

            'ano.numeric' =>'O campo Ano Calendário precisa ser um numeral.',
            'serie.numeric' => 'O campo Ano precisa ser um numeral.',

            'nome.unique' => 'Esta turma deste ano já possui no cadastro.',
        ];

        $validator = Validator::make($request->all(), $regras, $mensagens);

        if ($validator->fails()) {

            if ($request->ajax()) {
                return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()), 422);
            }

            $this->throwValidationException($request, $validator);
        }

        $turma = new Turma();
        $turma->tur_nome = $request->input('nome');
        $turma->tur_ano = $request->input('ano');
        $turma->tur_serie = $request->input('serie');
        $turma->tur_esc_id = $request->input('esc_id');
        $turma->tur_users_id_professor = $request->input('professor_id');
        $turma->save();
        $array = array(
            "id" => $turma->id,
            "tur_nome" => $turma->tur_nome,
            "tur_ano" => $turma->tur_ano,
            "tur_serie" => $turma->tur_serie
        );
        return json_encode($array);
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
        $turma = DB::table('turmas')
        ->join('users', 'turmas.tur_users_id_professor', '=', 'users.id')
        ->select('turmas.*', 'users.cpf', 'users.name')
        ->where('turmas.id','=',$id)
        ->get();
        if(isset($turma)){
            return json_encode($turma);
        }
        return response('Turma não encontrada!',400);

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
        $turma = Turma::find($id);

        $regras = [
            'nome' => "required",
            'ano' => "required|numeric",
            'serie' =>"required|numeric",
            'professor_id' =>"required",
        ];

        $mensagens = [
            'nome.required' => 'O campo Nome não pode estar em branco.',
            'ano.required' =>'O campo Ano Calendário não pode estar em branco.',
            'serie.required' => 'O campo Ano não pode estar em branco.',
            'professor_id.required' => 'O campo CPF do Professor não pode estar em branco.',

            'ano.numeric' =>'O campo Ano Calendário precisa ser um numeral.',
            'serie.numeric' => 'O campo Ano precisa ser um numeral.',
        ];

        $validator = Validator::make($request->all(), $regras, $mensagens);

        if ($validator->fails()) {

            if ($request->ajax()) {
                return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()), 422);
            }

            $this->throwValidationException($request, $validator);
        }


        if(isset($turma)){
            $turma->tur_nome = $request->input('nome');
            $turma->tur_ano = $request->input('ano');
            $turma->tur_serie = $request->input('serie');
            $turma->tur_esc_id = $request->input('esc_id');
            $turma->tur_users_id_professor = $request->input('professor_id');
            $turma->save();
            return json_encode($turma);
        }
        return response('Turma não encontrada!',400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $turma = Turma::find($id);
        if(isset($turma)){
            $turma->delete();
        }else{
            return response('Erro ao excluir!',400);
        }
    }
}
