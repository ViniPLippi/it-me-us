<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\Secretaria;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class SecretariaController extends Controller
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
        return view('secretarias');
    }

    public function indexJSON()
    {
        $secretarias = DB::table('secretarias')
        ->select('secretarias.*')
        ->orderBy('id')
        ->get();
        return Datatables::of($secretarias)
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
    public function create()
    {
        return view('novosecretaria');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $regras = [
            'cnpj' => 'required|digits:14|unique:secretarias,sec_cnpj',
            'razao' => 'required|max:100|regex:/^[A-Za-z]+$/|unique:secretarias,sec_razao',
            'cep' => 'required|digits:8|unique:secretarias,sec_cep',
            'tipo' => 'required',
            'logradouro' => 'required|max:100',
            'numero' => 'required|between:1,5',
            'complemento' => 'sometimes|max:20',
        ];

        $mensagens = [
            'cnpj.required' => 'O campo CNPJ não pode estar em branco.',
            'razao.required' => 'O campo Razão Social não pode estar em branco.',
            'cep.required' => 'O campo CEP não pode estar em branco.',
            'tipo.required' => 'Necessário selecionar algum Tipo de Secretaria.',
            'logradouro.required' => 'O campo Logradouro não pode estar em branco.',
            'numero.required' => 'O campo Número não pode estar em branco.',

            'cnpj.digits' => 'O campo CNPJ precisa ter 14 números.',
            'razao.max' => 'O campo Razão Social não pode ter mais de 100 caracteres.',
            'cep.digits' => 'O campo CEP precisa ter 8 números.',
            'logradouro.max' => 'O campo Logradouro precisa ter menos de 100 caracteres.',
            'numero.between' => 'O campo Número precisa ter 1 até 5 dígitos.',
            'complemento.max' => 'O campo Complemento precisa ter no máximo de 20 caracteres.',
            'razao.regex' => 'O campo Razão Social não pode ter numeral.',

            'cnpj.unique' => 'O CNPJ informado já existe no cadastro.',
            'razao.unique' => 'A Razão Social informada já existe no cadastro.',
            'cep.unique' => 'O CEP informado já existe no cadastro',
        ];

        //        $request->validate($regras, $mensagens);

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

        $secretaria = new Secretaria();
        $secretaria->sec_cnpj = $request->input('cnpj');
        $secretaria->sec_razao = $request->input('razao');
        $secretaria->sec_cep = $request->input('cep');
        $secretaria->sec_tipo = $request->input('tipo');
        $secretaria->sec_logradouro = $request->input('logradouro');
        $secretaria->sec_numero = $request->input('numero');
        if(empty($request->input('complemento'))){
            $secretaria->sec_complemento = 'Sem Complemento';
        }else{
            $secretaria->sec_complemento = $request->input('complemento');
        }
        $cidade = Cidade::where('cid_ibge','=',$request->input('idcid'))->first();
        $secretaria->sec_cid_id = $cidade->id;
        
        $secretaria->save();
        return json_encode($secretaria);
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
        $secretaria = DB::table('secretarias')
            ->join('cidades', 'secretarias.sec_cid_id', '=', 'cidades.id')
            ->join('estados', 'cidades.cid_est_id', '=', 'estados.id')
            ->select('secretarias.*', 'cidades.cid_nome', 'cidades.cid_ibge', 'estados.est_sigla')
            ->where('secretarias.id','=',$id)
            ->get();
        if (isset($secretaria)) {
            return json_encode($secretaria);
        }

        return response('secretaria não encontrado!', 400);
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
        $secretaria = Secretaria::find($id);

        $att_cep = $request->input('cep');
        $att_cid_id = $request->input('idcid');

        $mensagens = [
            'cnpj.required' => 'O campo CNPJ não pode estar em branco.',
            'razao.required' => 'O campo Razão Social não pode estar em branco.',
            'cep.required' => 'O campo CEP não pode estar em branco.',
            'tipo.required' => 'Necessario selecionar algum Tipo de Secretaria.',
            'logradouro.required' => 'O campo Logradouro não pode estar em branco.',
            'numero.required' => 'O campo Número não pode estar em branco.',
        
            'cnpj.digits' => 'O campo CNPJ precisa ter 14 números.',
            'razao.max' => 'O campo Razão Social não pode ter mais de 100 caracteres.',
            'cep.digits' => 'O campo CEP precisa ter 8 números.',
            'logradouro.max' => 'O campo Logradouro precisa ter menos de 100 caracteres.',
            'numero.between' => 'O campo Número precisa ter 1 até 5 dígitos.',
            'complemento.max' => 'O campo Complemento precisa ter máximo 20 caracteres.',
            'razao.regex' => 'O campo Razão Social não pode ter numeral.',

            'cnpj.unique' => 'O CNPJ informado já existe no cadastro.',
            'razao.unique' => 'A Razão Social informada já existe no cadastro.',
            'cep.unique' => 'O CEP informado já existe no cadastro',
            'logradouro.unique' => 'A Rua informada já existe no cadastro',
        ];

        $validator = Validator::make($request->all(), [
            'cnpj' => "required|digits:14|unique:secretarias,sec_cnpj,{$id},id",
            'razao' => "required|max:100|regex:/^[A-Za-z]+$/|unique:secretarias,sec_razao,{$id},id",
            'cep' => "required|digits:8|unique:secretarias,sec_cep,{$id},id",
            'tipo' => 'required',
            'logradouro' => "required|max:100|unique:secretarias,sec_logradouro,{$id},id",
            'numero' => 'required|between:1,5',
            'complemento' => 'sometimes|max:20',
        ], $mensagens);



        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()), 422);
            }
        }

        if (isset($secretaria)) {
            $secretaria->sec_cnpj = $request->input('cnpj');
            $secretaria->sec_razao = $request->input('razao');
            $secretaria->sec_cep = $request->input('cep');
            $secretaria->sec_tipo = $request->input('tipo');
            $secretaria->sec_logradouro = $request->input('logradouro');
            $secretaria->sec_numero = $request->input('numero');
            if(empty($request->input('complemento'))){
                $secretaria->sec_complemento = 'Sem Complemento';
            }else{
                $secretaria->sec_complemento = $request->input('complemento');
            }

            if (!empty($att_cep)) {
                $cidade = Cidade::where('cid_ibge', '=', $att_cid_id)->first();
                if ($cidade) {
                    $secretaria->sec_cid_id = $cidade->id;
                }
            }else {
                $mensagem = ['Cidade para o CEP informado não encontrada!'];
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $mensagem), 404);
            }
            
            $secretaria->save();
            return json_encode($secretaria);
        }

        return response('Secretaria não encontrada!', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $secretaria = Secretaria::find($id);
        if (isset($secretaria)) {
            $secretaria->delete();
        }
    }
}
