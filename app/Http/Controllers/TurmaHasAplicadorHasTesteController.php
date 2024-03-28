<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\TurmaHasAplicadorHasTeste;
use App\Models\AlunoHasTeste;

class TurmaHasAplicadorHasTesteController extends Controller
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
        $testes = DB::table('turmahasaplicadorhastestes')
            ->join('testes', 'turmahasaplicadorhastestes.tat_tst_id', '=', 'testes.id')
            ->select('turmahasaplicadorhastestes.id AS tat_id','turmahasaplicadorhastestes.tat_users_id_aplicador','tat_tur_id','testes.*')
            ->where('tat_tur_id', '=', $id)
            ->orderBy('id')
            ->get();
        return Datatables::of($testes)
            ->addColumn('action', function ($row) {
                $html = '<button type="button" class="btn btn-sm btn-danger mr-2" onclick="excluirTeste(' . $row->tat_id . ')">Excluir</button>';
                $html .= '<button type="button" class="btn btn-sm btn-success mr-2" onclick="carregarAplicador(' . $row->tat_users_id_aplicador . ')">Aplicador</button>';
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
        $tat = new TurmaHasAplicadorHasTeste();
        $tat->tat_users_id_aplicador = $request->input('professoraplic_id');
        $tat->tat_tur_id = $request->input('turma_id');
        $tat->tat_tst_id = $request->input('teste_id');
        $tat->tat_status = $request->input('status');
        $tat->save();

        $tst_id = $tat->tat_tst_id;
        $tat_id = $tat->id;
        $tur_id = $tat->tat_tur_id;
        $tat_users_id_aplicador = $tat->tat_users_id_aplicador;

        $tat = DB::table('turmahasaplicadorhastestes')
        ->join('testes', 'turmahasaplicadorhastestes.tat_tst_id', '=', 'testes.id')
        ->select('turmahasaplicadorhastestes.id','testes.tst_descricao','testes.tst_principal','testes.tst_ini','testes.tst_fim','testes.tst_status')
        ->get()->toJson();

        $thas = DB::table('turmahasalunos')
        ->join('turmas', 'turmahasalunos.tha_tur_id', '=', 'turmas.id')
        ->join('escolas', 'turmas.tur_esc_id', '=', 'escolas.id')
        ->join('cidades', 'escolas.esc_cid_id', '=', 'cidades.id')
        ->join('estados', 'cidades.cid_est_id', '=', 'estados.id')
        ->select('turmahasalunos.tha_alu_id','turmas.tur_esc_id','escolas.esc_cid_id','cidades.cid_est_id','turmas.tur_users_id_professor')
        ->where('turmahasalunos.tha_tur_id','=',$tur_id)
        ->get();

        if(isSet($tat)){
            if(isSet($thas)){
                foreach($thas as $tha){
                    $aht = new AlunoHasTeste();
                    $aht->aht_tat_id = $tat_id;
                    $aht->aht_alu_id = $tha->tha_alu_id;
                    $aht->aht_tst_id = $tst_id;
                    $aht->aht_tur_id = $tur_id;
                    $aht->aht_esc_id = $tha->tur_esc_id;
                    $aht->aht_cid_id = $tha->esc_cid_id;
                    $aht->aht_est_id = $tha->cid_est_id;
                    $aht->aht_users_id_professor = $tha->tur_users_id_professor;
                    $aht->aht_users_id_aplicador = $tat_users_id_aplicador;
                    $aht->aht_grupoaplic = 0;
                    $aht->aht_ind11 = 0;
                    $aht->aht_ind12 = 0;
                    $aht->aht_ind21 = 0;
                    $aht->aht_ind22 = 0;
                    $aht->aht_ind31 = 0;
                    $aht->aht_ind32 = 0;
                    $aht->aht_arqaudio1 = "";
                    $aht->aht_arqaudio2 = "";
                    $aht->aht_arqaudio3 = "";
                    $aht->aht_status = 0;
                    $aht->aht_statusarq1  = 0;
                    $aht->aht_statusarq2 = 0;
                    $aht->aht_statusarq3 = 0;
                    $aht->save();
                }
                return $tat;
            }else{
                return response("Falha ao criar registros de alunos desse Teste da turma!",400);
            }
            return $tat;
        }else{
            return response("Falha ao carregar esse Teste da turma!",400);
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
        DB::delete('DELETE FROM alunohastestes WHERE aht_tat_id = ?', [$id]);

        $tat = TurmaHasAplicadorHasTeste::find($id);

        if(isset($tat)){
            $tat->delete();
        }

        return response('Teste excluído com Sucesso!',200);
    }
}
