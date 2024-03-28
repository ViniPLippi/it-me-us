<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isEmpty;

class EstadoController extends Controller
{
    public function __construct()
    {
    }

    public function indexView()
    {
        // $estados = Estado::all();

        return view('estados');
    }

    public function indexdtJson()
    {
        $estados = Estado::orderBy('id')->get();
        return Datatables::of($estados)
            ->addColumn('action', function ($row) {
                $html ='<a href="/estados/'.$row->id.'" class="btn btn-sm btn-primary" role="button">Editar </a>'.' ';
                $html = '<button class="btn btn-sm btn-primary" onclick="editar(' . $row->id . ')">Editar</button>' . ' ';
                $html .= '<button class="btn btn-sm btn-danger" onclick="excluir(' . $row->id . ')">Excluir</button>';
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function indexJSON()
    {
        return Estado::orderBy('id')->paginate(10);
    }

    public function indexJsonA()
    {
        $estados = Estado::orderBy('est_nome')->get();
        return $estados;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('novoestado');
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
            'est_nome' => 'required|max:45|regex:/^[A-Za-z]+$/|unique:estados',
            'est_sigla' => 'required|min:2|max:2|regex:/^[A-Za-z]+$/|unique:estados',
            'est_ibge' => 'required|min:2|unique:estados'
        ];        

        $mensagens = [
            'est_nome.required' => 'O campo Nome não pode estar em branco.',
            'est_sigla.required' => 'O campo Sigla não pode estar em branco.',
            'est_ibge.required' => 'O campo IBGE não pode estar em branco.',

            'est_nome.regex' => 'O campo Nome não pode ter numeral.',
            'est_sigla.regex' => 'O campo Sigla não pode ter numeral.',

            'est_nome.max' => 'O campo nome não pode ter mais de 45 caracteres.',
            'est_sigla.min' => 'O campo sigla não pode ter menos de 2 caracteres.',
            'est_sigla.max' => 'O campo sigla não pode ter mais de 2 caracteres.',
            'est_ibge.max' => 'O campo IBGE não pode ter menos de 2 caracteres.',
            'est_ibge.min' => 'O campo IBGE não pode ter menos de 2 caracteres.',

            'est_nome.unique' => 'O Estado informado já possui no cadastro.',
            'est_sigla.unique' => 'A Sigla informada já foi utilizada.',
            'est_ibge.unique' => 'O código IBGE informado já possui um Estado.'
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

        $estado = new Estado();
        $estado->est_nome = $request->input('est_nome');
        $estado->est_sigla = strtoupper($request->input('est_sigla'));
        $estado->est_ibge = $request->input('est_ibge');
        $estado->save();

        return json_encode($estado);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function show(Estado $estado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $estado = Estado::find($id);
        if (isset($estado)) {
            return json_encode($estado);
        }
        return response('Estado não encontrado!', 400);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $estado = Estado::find($id);

        $attnome = $request->input('nome');

        $attsigla = $request->input('sigla');

        $attibge = $request->input('ibge');

        if(isEmpty($attnome, $attsigla, $attibge)){

            $regras = [
                'nome' => "required|max:45|regex:/^[A-Za-z]+$/|unique:estados,est_nome,{$id},id",
                'sigla' => "required|between:2,2|regex:/^[A-Za-z]+$/|unique:estados,est_sigla,{$id},id",
                'ibge' => "required|between:2,3|unique:estados,est_ibge,{$id},id"
            ];

            $mensagens = [
                'nome.required' => 'O campo Nome não pode estar em branco.',
                'nome.max' => 'O campo Nome não pode ter mais de 45 caracteres.',
                'nome.unique' => 'O Nome informado já possui no cadastro.',

                'nome.regex' => 'O campo Nome não pode ter numeral.',
                'sigla.regex' => 'O campo Sigla não pode ter numeral.',

                'sigla.required' => 'O campo Sigla não pode estar em branco.',
                'sigla.between' => 'O campo Sigla deve possuir 2 caracteres.',
                'sigla.unique' => 'A Sigla do Estado informado já possui no cadastro.',

                'ibge.required' => 'O campo IBGE não pode estar em branco.',
                'ibge.between' => 'O campo IBGE deve possuir ao menos 2 caracteres.',
                'ibge.unique' => 'O código IBGE informado já possui um Estado.',
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
                if(isset($estado)){
                    $estado->est_nome = $request->input('nome');
                    $estado->est_sigla = strtoupper($request->input('sigla'));
                    $estado->est_ibge = $request->input('ibge');
                    $estado->update();
                    return json_encode($estado);
                    return response('Estado não encontrado!',400);
                }
            }

            $this->throwValidationException($request, $validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $estado = Estado::find($id);

        $estcid = DB::table('cidades')
        ->join('estados', 'estados.id', '=', 'cidades.cid_est_id')
        ->selectRaw('count(cidades.cid_est_id), cidades.cid_est_id')
        ->addSelect('estados.est_nome')
        ->groupBy('cidades.cid_est_id', 'estados.est_nome')->orderby('cid_est_id')
        ->get();
        $array = json_decode($estcid);

        $idest = (int) $estado->id;
        

        $fk = array_column($array, 'cid_est_id');
        $found_key = array_search($idest, $fk);

        if($found_key !== false){
            $mensagem = ['Necessário a exclusão das Cidades agregadas neste Estado!'];
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $mensagem), 409);
        }else{
            if(isset($estado)) {
                $estado->delete();
            }
        }        
    }

    public function getSigla($id)
    {
        $estado = Estado::find($id);
        if (isset($estado)) {
            return $estado->est_sigla;
        }
        return "N/D";
    }
}
