<?php

namespace App\Http\Controllers;

use App\Models\Escola;
use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EscolaController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('escolas');
    }

    public function indexJSON($id)
    {
        if ($id > -1) {
            $escolas = DB::table('escolas')
                ->join('cidades', 'escolas.esc_cid_id', '=', 'cidades.id')
                ->join('estados', 'cidades.cid_est_id', '=', 'estados.id')
                ->select('escolas.id', 'escolas.esc_razao', 'cidades.cid_nome', 'escolas.esc_inep',)
                ->where('estados.id', '=', $id)
                ->orderBy('id')
                ->get();
        } else {
            $escolas = DB::table('escolas')
                ->join('cidades', 'escolas.esc_cid_id', '=', 'cidades.id')
                ->join('estados', 'cidades.cid_est_id', '=', 'estados.id')
                ->select('escolas.id', 'escolas.esc_razao', 'cidades.cid_nome', 'escolas.esc_inep',)
                ->orderBy('id')
                ->get();
        }
        return Datatables::of($escolas)
            ->addColumn('action', function ($row) {
                $html = '<button type="button" class="btn btn-sm btn-primary" onclick="editar(' . $row->id . ')">Editar</button>' . ' ';
                $html .= '<button type="button" class="btn btn-sm btn-danger" onclick="excluir(' . $row->id . ')">Excluir</button>';
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function indexINEP($id)
    {
        $escola = DB::table('escolas')
            ->join('cidades', 'escolas.esc_cid_id', '=', 'cidades.id')
            ->join('estados', 'cidades.cid_est_id', '=', 'estados.id')
            ->select('escolas.*', 'cidades.cid_nome', 'cidades.cid_ibge', 'estados.est_sigla')
            ->where('escolas.esc_inep', '=', $id)
            ->get();
        if (isset($escola)) {
            return json_encode($escola);
        }
        return response('Escola não encontrada!', 400);
    }

    public function indexCidade($id)
    {
        $escolas = DB::table('escolas')
            ->join('cidades', 'escolas.esc_cid_id', '=', 'cidades.id')
            ->join('estados', 'cidades.cid_est_id', '=', 'estados.id')
            ->select('escolas.id', 'escolas.esc_razao', 'cidades.cid_nome', 'escolas.esc_inep',)
            ->where('cidades.id', '=', $id)
            ->orderBy('escolas.esc_razao')
            ->get();
        return $escolas;
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
        $escola = new Escola();

        $regras = [
            'razao' => "required|between:10,100",
            'inep' => "required|digits:8|unique:escolas,esc_inep",
            'localizacao' => "",
            'restricao' => "",
            'logradouro' => "required|between:5,150",
            'telefone' => "required|between:14,15",
            'localdif' => "required",
            'catadm' => "",
            'depadm' => "required",
            'convpodpub' => "",
            'regconsedu' => "",
            'porte' => "required|max:50",
            'etamodensofe' => "required|max:120",
            'outofeens' => "max:100",
            'latitude' => "nullable", //Rever como analisar lat e long
            'longitude' => "nullable", //Rever como analisar lat e long
            'cep' => "required|digits:8",
            'bairro' => "required|max:100",
        ];

        $mensagens = [
            'razao.required' => 'O campo Razão Social não pode estar em branco.',
            'inep.required' => 'O campo Código INEP não pode estar em branco.',
            'localizacao.required' => 'O campo Localização não pode estar em branco.',
            'logradouro.required' => 'O campo Logradouro não pode estar em branco.',
            'telefone.required' => 'O campo Telefone não pode estar em branco.',
            'localdif.required' => 'O campo Localidade Diferenciada precisa ter algo selecionado.',
            'depadm.required' => 'O campo Dependência Administrativa precisa ter algo selecionado',
            'porte.required' => 'O campo Porte não pode estar em branco.',
            'etamodensofe.required' => 'O campo Etapas e Modalidade de Ensino Oferecidas não pode estar em branco.',
            'cep.required' => 'O campo CEP não pode estar em branco.',
            'bairro.required' => 'O campo Bairro não pode estar em branco.',

            'razao.between' => 'O campo Razão Social precisa ter entre 10 e 100 caracteres.',
            'inep.digits' => 'O campo Código INEP precisa ter 8 números.',
            'localizacao.between' => 'O campo Localização precisa ter no máximo 150 caracteres.',
            'logradouro.max' => 'O campo Logradouro precisa ter no máximo 150 caracteres.',
            'telefone.between' => 'O campo Telefone precisa ter o DDD e o número completo.',
            'porte.max' => 'O campo Porte precisa ter no máximo 50 caracteres.',
            'etamodensofe.digits' => 'O campo Etapas e Modalidade de Ensino Oferecidas precisa ter no máximo 120 caracteres.',
            'outofeens.digits' => 'O campo Outras Ofertas Educacionais precisa ter no máximo 100 caracteres.',
            'cep.digits' => 'O campo CEP precisa ter 8 números.',
            'bairro.max' => 'O campo Bairro precisa ter no máximo 100 caracteres.',

            'inep.unique' => 'O Código INEP informado, pertence a uma escola cadastrada.',
        ];

        $validator = Validator::make($request->all(), $regras, $mensagens);

        if ($validator->fails()) {

            if ($request->ajax()) {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            }

            $this->throwValidationException($request, $validator);
        }

        $escola->esc_razao = $request->input('razao');
        $escola->esc_inep = $request->input('inep');
        $escola->esc_localizacao = $request->input('localizacao');
        $escola->esc_restricao = $request->input('restricao');
        $escola->esc_logradouro = $request->input('logradouro');
        $escola->esc_telefone = $request->input('telefone');
        $escola->esc_local_dif = $request->input('localdif');
        $escola->esc_cat_adm = $request->input('catadm');
        $escola->esc_dep_adm = $request->input('depadm');
        $escola->esc_cat_esc_priv = 1;
        $escola->esc_conv_pod_pub = $request->input('convpodpub');
        $escola->esc_reg_cons_edu = $request->input('regconsedu');
        $escola->esc_porte = $request->input('porte');
        $escola->esc_eta_mod_ens_ofe = $request->input('etamodensofe');
        $escola->esc_out_ofe_ens = $request->input('outofeens');
        $escola->esc_latitude = $request->input('latitude');
        $escola->esc_longitude = $request->input('longitude');
        $escola->esc_cep = $request->input('cep');
        $escola->esc_bairro = $request->input('bairro');
        $cidade = Cidade::where('cid_ibge', '=', $request->input('cid_ibge'))->first();
        $escola->esc_cid_id = $cidade->id;
        $escola->save();
        $array = array(
            "id" => $escola->id,
            "esc_razao" => $escola->esc_razao,
            "esc_inep" => $escola->esc_inep,
            "cid_nome" => $cidade->cid_nome
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
        $escola = DB::table('escolas')
            ->join('cidades', 'escolas.esc_cid_id', '=', 'cidades.id')
            ->join('estados', 'cidades.cid_est_id', '=', 'estados.id')
            ->select('escolas.*', 'cidades.cid_nome', 'cidades.cid_ibge', 'estados.est_sigla')
            ->where('escolas.id', '=', $id)
            ->get();
        if (isset($escola)) {
            return json_encode($escola);
        }
        return response('Escola não encontrada!', 400);
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
        $escola = Escola::find($id);

        $regras = [
            'razao' => "required|between:5,100|unique:escolas,esc_razao,{$id},id",
            //'inep' => "required|digits:8|unique:escolas,esc_inep,{$id},id",
            'localizacao' => "",
            'restricao' => "",
            'logradouro' => "required|between:5,150",
            'telefone' => "required|between:14,15|unique:escolas,esc_telefone,{$id},id",
            'localdif' => "required",
            'catadm' => "",
            'depadm' => "required",
            'convpodpub' => "",
            'regconsedu' => "",
            'porte' => "required|max:50",
            'etamodensofe' => "required|max:120",
            'outofeens' => "max:100",
            'latitude' => "nullable", //Rever como analisar lat e long
            'longitude' => "nullable", //Rever como analisar lat e long
            'cep' => "required|digits:8|unique:escolas,esc_cep,{$id},id",
            'bairro' => "required|max:100|unique:escolas,esc_bairro,{$id},id",
        ];

        $mensagens = [
            'razao.required' => 'O campo Razão Social não pode estar em branco.',
            //'inep.required' =>'O campo Código INEP não pode estar em branco.',
            'logradouro.required' => 'O campo Logradouro não pode estar em branco.',
            'telefone.required' => 'O campo Telefone não pode estar em branco.',
            'localdif.required' => 'O campo Localidade Diferenciada precisa ter algo selecionado.',
            'depadm.required' => 'O campo Dependência Administrativa precisa ter algo selecionado',
            'porte.required' => 'O campo Porte não pode estar em branco.',
            'etamodensofe.required' => 'O campo Etapas e Modalidade de Ensino Oferecidas não pode estar em branco.',
            'cep.required' => 'O campo CEP não pode estar em branco.',
            'bairro.required' => 'O campo Bairro não pode estar em branco.',

            'razao.between' => 'O campo Razão Social precisa entre 10 e 100 caracteres.',
            //'inep.digits' =>'O campo Código INEP precisa ter 8 números.',
            'localizacao.max' => 'O campo Localização precisa ter no máximo 150 caracteres.',
            'logradouro.between' => 'O campo Logradouro precisa ter no máximo 150 caracteres.',
            'telefone.between' => 'O campo Telefone precisa ter o DDD e o número completo.',
            'porte.max' => 'O campo Porte precisa ter no máximo 50 caracteres.',
            'etamodensofe.digits' => 'O campo Etapas e Modalidade de Ensino Oferecidas precisa ter no máximo 120 caracteres.',
            'outofeens.digits' => 'O campo Outras Ofertas Educacionais precisa ter no máximo 100 caracteres.',
            'cep.digits' => 'O campo CEP precisa ter 8 números.',
            'bairro.max' => 'O campo Bairro precisa ter no máximo 100 caracteres.',

            'razao.unique' => 'A Razão Social informada, pertence a uma escola cadastrada.',
            //'inep.unique' =>'O Código INEP informado, pertence a uma escola cadastrada.',
            'telefone.unique' => 'O Telefone informado, pertence a uma escola cadastrada.',
            'cep.unique' => 'O CEP informado, pertence a uma escola cadastrada.',
            'bairro.unique' => 'O Bairro informado, pertence a uma escola cadastrada.',
        ];

        $validator = Validator::make($request->all(), $regras, $mensagens);

        if ($validator->fails()) {

            if ($request->ajax()) {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            }

            $this->throwValidationException($request, $validator);
        }

        if (isset($escola)) {
            $escola->esc_razao = $request->input('razao');
            $escola->esc_inep = $request->input('inep');
            $escola->esc_localizacao = $request->input('localizacao');
            $escola->esc_restricao = $request->input('restricao');
            $escola->esc_logradouro = $request->input('logradouro');
            $escola->esc_telefone = $request->input('telefone');
            $escola->esc_local_dif = $request->input('localdif');
            $escola->esc_cat_adm = $request->input('catadm');
            $escola->esc_dep_adm = $request->input('depadm');
            $escola->esc_cat_esc_priv = 1;
            $escola->esc_conv_pod_pub = $request->input('convpodpub');
            $escola->esc_reg_cons_edu = $request->input('regconsedu');
            $escola->esc_porte = $request->input('porte');
            $escola->esc_eta_mod_ens_ofe = $request->input('etamodensofe');
            $escola->esc_out_ofe_ens = $request->input('outofeens');
            $escola->esc_latitude = $request->input('latitude');
            $escola->esc_longitude = $request->input('longitude');
            $escola->esc_cep = $request->input('cep');
            $escola->esc_bairro = $request->input('bairro');
            $cidade = Cidade::where('cid_ibge', '=', $request->input('cid_ibge'))->first();
            $escola->esc_cid_id = $cidade->id;
            $escola->save();
            return json_encode($escola);
        }
        return response('Escola não encontrada!', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $escola = Escola::find($id);
        if (isset($escola)) {
            $escola->delete();
        }
    }
}
