<?php

namespace App\Http\Controllers;

use App\Models\TurmaHasAluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

class TurmaHasAlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function indexJSON($id)
    {
        $alunos = DB::table('turmahasalunos')
        ->join('alunos', 'turmahasalunos.tha_alu_id', '=', 'alunos.id')
        ->select('alunos.alu_nome','alunos.alu_rge','turmahasalunos.id')
        ->where("turmahasalunos.tha_tur_id","=",$id)
        ->orderBy('alunos.alu_nome')
        ->get();
        if(isSet($alunos)){
            return Datatables::of($alunos)
                ->addColumn('action', function ($row) {
                    $html = '<button type="button" class="btn btn-sm btn-danger" onclick="excluirAluno(' . $row->id . ')">Excluir</button>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->toJson();
        }else{
            return response("Falha ao carregar alunos da turma!",400);
        }
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
        $aluno_id = $request->input('aluno_id');
        $turma_id = $request->input('turma_id');

        $ano_turma = DB::table('turmas')->where('id', $turma_id)->value('tur_ano');
        $serie_turma = DB::table('turmas')->where('id', $turma_id)->value('tur_serie');

        $aluno_na_mesma_serie_no_ano = DB::table('alunos')
            ->join('turmahasalunos', 'alunos.id', '=', 'turmahasalunos.tha_alu_id')
            ->join('turmas', 'turmas.id', '=', 'turmahasalunos.tha_tur_id')
            ->where('turmas.tur_ano', $ano_turma)
            //->where('turmas.tur_serie', $serie_turma)
            ->where('alunos.id', $aluno_id)
            ->count();

        if($aluno_na_mesma_serie_no_ano > 0){
            $mensagens = ['O Aluno já está cadastrado em uma Turma deste mesmo Ano.'];
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $mensagens), 422);
        }else{
            $turmahasaluno = new TurmaHasAluno();
            $turmahasaluno->tha_tur_id = $request->input('turma_id');
            $turmahasaluno->tha_alu_id = $request->input('aluno_id');
            $turmahasaluno->save();

            $aluno = DB::table('turmahasalunos')
            ->join('alunos', 'turmahasalunos.tha_alu_id', '=', 'alunos.id')
            ->select('alunos.alu_nome','alunos.alu_rge','turmahasalunos.id')
            ->where("turmahasalunos.tha_tur_id",$request->input('turma_id'))
            ->where("alunos.id",$request->input('aluno_id'))
            ->get()->toJson();
            if(isSet($aluno)){
                info($aluno);
                return $aluno;
            }else{
                return response("Falha ao carregar esse aluno da turma!",400);
            }
        }
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $turma = TurmaHasAluno::find($id);
        if(isset($turma)){
            $turma->delete();
        }else{
            return response('Erro ao excluir!',400);
        }
    }
}
