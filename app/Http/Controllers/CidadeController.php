<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isEmpty;

class CidadeController extends Controller
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
        //        $estados = Estado::all();
        return view('cidades');
    }

    public function indexJSON()
    {
        $cidades = DB::table('cidades')
            ->join('estados', 'cidades.cid_est_id', '=', 'estados.id')
            ->select('cidades.id','cidades.cid_nome','cidades.cid_ibge','estados.est_sigla')
            ->orderBy('cidades.id')
            ->get();
        return Datatables::of($cidades)
            ->addColumn('action', function ($row) {
                $html = '<button type="button" class="btn btn-sm btn-primary" onclick="editar(' . $row->id . ')">Editar</button>' . ' ';
                $html .= '<button type="button" class="btn btn-sm btn-danger" onclick="excluir(' . $row->id . ')">Excluir</button>';
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
        return view('novocidade');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $estado = request('estado');
        $nome = 'required|max:45|regex:/^[A-Za-z]+$/|unique:cidades,cid_nome,NULL,id,cid_est_id';
        if(!is_null($estado)){
            $nome .= ",{$estado}";
        }
        $regras = [
            'ibge' => "required|min:7|max:7|unique:cidades,cid_ibge",
            'nome' => $nome,
            'estado' =>"required",
            'tastatus' => "required",
            'taadesao' => "required_unless:tastatus,nd",
            'taexclusao' => "sometimes|nullable|after_or_equal:taadesao",
        ];

        $mensagens = [
            'ibge.required' => 'O campo IBGE não pode estar em branco.',
            'nome.required' => 'O campo Nome não pode estar em branco.',
            'estado.required' =>'Necessário selecionar um Estado para salvar.',
            'tastatus.required' => 'O botão de Adesão precisa ser selecionado.',
            'taadesao.required_unless' => 'Necessário inclusão da Data de Adesão.',
            'taexclusao.after_or_equal' => 'A Data de Exclusão não pode ser posterior a Data de Adesão.',
            'nome.regex' => 'O campo Nome não pode ter numeral.',

            'ibge.min' => 'O campo IBGE não pode ter menos de 7 caracteres.',
            'ibge.max' => 'O campo IBGE não pode ter mais de 7 caracteres.',
            'nome.max' => 'O campo Nome não pode ter mais de 45 caracteres.',

            'ibge.unique' => 'O código IBGE informado já possui uma Cidade.',
            'nome.unique' => 'Existe uma Cidade cadastrada para esse Estado.',
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

        $cidade = new Cidade();
        $cidade->cid_nome = $request->input('nome');
        $cidade->cid_est_id = $request->input('estado');
        $cidade->cid_ibge = $request->input('ibge');
        if ($request->input('tastatus') == "ad") {
            $cidade->cid_ta_status = 1;
        } else {
            $cidade->cid_ta_status = 0;
        }
        $cidade->cid_ta_adesao = $request->input('taadesao');
        $cidade->cid_ta_exclusao = $request->input('taexclusao');
        $cidade->save();
        return json_encode($cidade);
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
        $cidade = DB::table('cidades')
            ->join('estados', 'cidades.cid_est_id', '=', 'estados.id')
            ->select('cidades.*', 'estados.est_nome')
            ->where('cidades.id','=',$id)
            ->get();
        if(isset($cidade)){
            return json_encode($cidade);
        }
        return response('Cidade não encontrada!',400);
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
        $cidade = Cidade::find($id);

        $attnome = $request->input('nome');

        $attestado = $request->input('estado');

        $attibge = $request->input('ibge');

        if(isEmpty($attnome, $attestado, $attibge)){

            $regras = [
                'nome' => "required|string|unique:cidades,cid_nome,{$id},id",
                'ibge' => "required|min:7|max:7|unique:cidades,cid_ibge,{$id},id",
                'estado' =>"required|unique:cidades,cid_est_id,{$id},id",
                'tastatus' => "required",
                'taadesao' => "required_if:tastatus, ==, ad|date",
                //'taexclusao' => "required_if:tastatus, ==, ad|date",
            ];

            $mensagens = [
                'ibge.required' => 'O campo IBGE não pode estar em branco.',
                'nome.required' => 'O campo Nome não pode estar em branco.',
                'estado.required' =>'Necessário selecionar um Estado para salvar.',
                'tastatus.required' => 'O botão de Adesão precisa ser selecionado.',
                'taadesao.required' => 'A data de Adesão não pode estar em branco.',
                'taexclusao.required' => 'A data de Exclusão não pode estar em branco.',

                'ibge.min' => 'O campo IBGE não pode ter menos de 7 caracteres.',
                'ibge.max' => 'O campo IBGE não pode ter mais de 7 caracteres.',
                'nome.max' => 'O campo Nome não pode ter mais de 45 caracteres.',

                'ibge.unique' => 'O código IBGE informado já possui uma Cidade.',
                'nome.unique' => 'A Cidade informada já possui no cadastro.',
                'estado.unique' => 'A Estado informado já foi utilizada para esta Cidade.',

                'taadesao.date' => 'Selecione uma data de Adesão.',
                'taexclusao.date' => 'Selecione uma data de Exclusão.',
            ];

            $validator = Validator::make($request->all(), $regras, $mensagens);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()), 422);
                }
            }else{
                if(isset($cidade)){
                    $cidade->cid_nome = $request->input('nome');
                    $cidade->cid_est_id = $request->input('estado');
                    $cidade->cid_ibge = $request->input('ibge');
                    if ($request->input('tastatus') == "ad") {
                        $cidade->cid_ta_status = 1;
                    } else {
                        $cidade->cid_ta_status = 0;
                    }
                    $cidade->cid_ta_adesao = $request->input('taadesao');
                    $cidade->cid_ta_exclusao = $request->input('taexclusao');
                    $cidade->save();
                    return json_encode($cidade);
                }
            }
            $this->throwValidationException($request, $validator);
        }
        return response('Cidade não encontrada!',400);
    }
//        return redirect('/estados');

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        $cidade = Cidade::find($id);

        $cidsec = DB::table('secretarias')
        ->join('cidades', 'cidades.cid_ibge', '=', 'secretarias.sec_cid_id')
        ->selectRaw('count(secretarias.sec_cid_id) as total, secretarias.sec_cid_id')
        ->addSelect('cidades.cid_nome')
        ->groupBy('secretarias.sec_cid_id', 'cidades.cid_nome')
        ->orderBy('secretarias.sec_cid_id')
        ->get();

        $array = json_decode($cidsec);

        $cidesc = DB::table('escolas')
        ->join('cidades', 'cidades.id', '=', 'escolas.esc_cid_id')
        ->selectRaw('count(escolas.esc_cid_id) as total, escolas.esc_cid_id')
        ->addSelect('cidades.cid_nome')
        ->groupBy('escolas.esc_cid_id', 'cidades.cid_nome')
        ->orderBy('escolas.esc_cid_id')
        ->get();

        $array2 = json_decode($cidesc);

        $idcid = (int) $cidade->id;
        

        $fk2 = array_column($array2, 'esc_cid_id');
        $found_key2 = array_search($idcid, $fk2);
        
        $ibgecid = (int) $cidade->cid_ibge;
        
        $fk = array_column($array, 'sec_cid_id');
        $found_key = array_search($ibgecid, $fk);

        if($found_key2 !== false || $found_key !== false){
            $mensagens = ['Necessário a exclusão das Escolas e/ou Secretarias agregadas nesta Cidade!'];
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $mensagens), 409);
        }else{
            if(isset($cidade)){
                $cidade->delete();
            }
        } 
    }

    public function indexUF($uf)
    {
        $cidades = DB::table('cidades')
            ->join('estados', 'cidades.cid_est_id', '=', 'estados.id')
            ->select('cidades.id','cidades.cid_nome','cidades.cid_ibge','estados.est_sigla')
            ->where('estados.id',"=",$uf)
            ->orderBy('cidades.cid_nome')
            ->get();
        return $cidades;
    }
}
