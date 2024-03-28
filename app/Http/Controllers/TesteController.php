<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teste;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TesteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('testes');
    }

    public function sala()
    {
        return view('sala');
    }

    public function indexAPIJson($cpf)
    {
        $teste = DB::table('users')
            ->select('users.id', 'users.name', 'users.cpf', 'users.tipo')
            ->where('users.cpf', '=', $cpf)
            ->get();
        $retorno = '{"usuario":{"id":' . $teste[0]->id . ', "name":"' . $teste[0]->name . '","cpf":"' . $teste[0]->cpf . '","tipo":"' . (is_null($teste[0]->tipo) ? "PF" : $teste[0]->tipo) . '"';
        $testes = DB::table('turmahasaplicadorhastestes AS tat')
            ->join('testes', 'tat.tat_tst_id', '=', 'testes.id')
            ->join('turmas', 'tat.tat_tur_id', '=', 'turmas.id')
            ->join('escolas', 'turmas.tur_esc_id', '=', 'escolas.id')
            ->join('cidades', 'escolas.esc_cid_id', '=', 'cidades.id')
            ->join('estados', 'cidades.cid_est_id', "=", 'estados.id')
            ->select(
                'tat.id AS tat_id',
                'tat.tat_tst_id',
                'testes.tst_status',
                'escolas.id AS esc_id',
                'escolas.esc_inep',
                'escolas.esc_razao',
                'cidades.cid_nome',
                'estados.est_sigla',
                'tat.tat_tur_id',
                'turmas.tur_nome',
                'turmas.tur_ano',
                'turmas.tur_serie'
            )
            ->where('tat_users_id_aplicador', '=', $teste[0]->id)
            ->get();
        $retorno .= ', "turmas":[';
        for ($x = 0; $x < sizeof($testes); $x++) {
            $retorno .= '{ "tat.id":' . $testes[$x]->tat_id . ', "teste.id":' . $testes[$x]->tat_tst_id . ', "teste.status":' . $testes[$x]->tst_status .
                ', "escola.id":' . $testes[$x]->esc_id . ', "escola.inep":"' . $testes[$x]->esc_inep . '", "escola.razao":"' . $testes[$x]->esc_razao .
                '", "cidade.nome":"' . $testes[$x]->cid_nome . '", "estado.sigla":"' . $testes[$x]->est_sigla .
                '", "turma.id":' . $testes[$x]->tat_tur_id . ', "turma.nome":"' . $testes[$x]->tur_nome . '", "turma.ano":' . $testes[$x]->tur_ano . ', "turma.serie":' . $testes[$x]->tur_serie .
                ', ';
            $alunos = DB::table('turmahasalunos AS tha')
                ->join('alunos', 'tha.tha_alu_id', '=', 'alunos.id')
                ->join('turmahasaplicadorhastestes AS tat','tha.tha_tur_id','=','tat.tat_tur_id')
                ->join("alunohastestes AS aht",function($join){
                    $join->on("aht.aht_tat_id","=",'tat.id')
                        ->on("aht.aht_alu_id","=","alunos.id");
                })
                ->select('alunos.id', 'alunos.alu_nome', 'alunos.alu_rge', 'alunos.alu_nasc','aht.id AS aht_id')
                ->where('tha.tha_tur_id', '=', $testes[$x]->tat_tur_id)
                ->get();
            $retorno .= '"alunos":[';
            for ($y = 0; $y < sizeof($alunos); $y++) {
                $retorno .= '{ "aluno_id":' . $alunos[$y]->id .
                    ', "nome":"' . $alunos[$y]->alu_nome .
                    '", "rge":"' . $alunos[$y]->alu_rge .
                    '", "data_nasc":"' . $alunos[$y]->alu_nasc .
                    '", "aht_id":"' . $alunos[$y]->aht_id;
                if ($y == sizeof($alunos) - 1) {
                    $retorno .= '"}';
                } else {
                    $retorno .= '"},';
                }
            }
            if ($x == sizeof($testes) - 1) {
                $retorno .= ']} ';
            } else {
                $retorno .= ']}, ';
            }
        }
        $retorno .= ']}}';
        return response($retorno)
            ->header('content-Type', 'application/json; charset=UTF-8')
            ->header('meta', 'http-equiv="Content-Security-Policy" content="upgrade-insecure-requests');
    }

    public function indexJSON()
    {
        $testes = DB::table('testes')
            ->select('testes.*')
            ->orderBy('id')
            ->get();
        return Datatables::of($testes)
            ->addColumn('action', function ($row) {
                $html = '<button type="button" class="btn btn-sm btn-primary" onclick="editar(' . $row->id . ')">Editar</button>' . ' ';
                $html .= '<button type="button" class="btn btn-sm btn-danger" onclick="excluir(' . $row->id . ')">Excluir</button>';
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function indexJSONF()
    {
        $testes = DB::table('testes')
            ->select('testes.*')
            ->orderBy('id')
            ->get();
            return $testes;
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
        $teste = new Teste();
        $teste->tst_sec_id = $request->input('sec_id');
        $teste->tst_esc_id = $request->input('esc_id');
        $teste->tst_descricao = $request->input('descricao');
        $teste->tst_principal = $request->input('principal');
        $teste->tst_status = $request->input('status');
        $teste->tst_ini = $request->input('inicio');
        $teste->tst_fim = $request->input('fim');
        $teste->tst_data_iniadesao = $request->input('iniadesao');
        $teste->tst_data_fimadesao = $request->input('fimadesao');
        $teste->tst_palavras = $request->input('palavras');
        $teste->tst_pseudopalavras = $request->input('pseudos');
        $teste->tst_texto = $request->input('texto');
        $teste->tst_id_treinamento = $request->input('treinamento');
        $teste->save();
        $array = array(
            "id" => $teste->id,
            "tst_descricao" => $teste->tst_descricaod,
            "tst_principal" => $teste->tst_pricipal,
            "tst_ini" => $teste->tst_ini,
            "tst_fim" => $teste->tst_fim,
            "tst_status" => $teste->tst_status,
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

        $teste = DB::table('testes')
        ->join('secretarias', 'testes.tst_sec_id', '=', 'secretarias.id')
        ->leftJoin('escolas', function ($join) {
            $join->on('testes.tst_esc_id', '=', 'escolas.id')
                 ->where('testes.tst_esc_id', '>', 0);
        })
        ->select('testes.*','secretarias.sec_razao', 'secretarias.sec_cnpj', 'escolas.esc_razao', 'escolas.esc_inep')
        ->where('testes.id','=',$id)
        ->get();
        if(isset($teste)){
            return json_encode($teste);
        }
        return response('Teste não encontrado!',400);

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
        $teste = Teste::find($id);
        if(isset($teste)){
            $teste->tst_descricao = $request->input('descricao');
            $teste->tst_sec_id = $request->input('sec_id');
            $teste->tst_esc_id = $request->input('esc_id');
            $teste->tst_principal = $request->input('principal');
            $teste->tst_status = $request->input('status');
            $teste->tst_ini = $request->input('inicio');
            $teste->tst_fim = $request->input('fim');
            $teste->tst_data_iniadesao = $request->input('iniadesao');
            $teste->tst_data_fimadesao = $request->input('fimadesao');
            $teste->tst_palavras = $request->input('palavras');
            $teste->tst_pseudopalavras = $request->input('pseudos');
            $teste->tst_texto = $request->input('texto');
            $teste->save();
            return json_encode($teste);
        }
        return response('Teste não encontrado!',400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teste = Teste::find($id);
        if(isset($teste)){
            $teste->delete();
        }

    }
}
