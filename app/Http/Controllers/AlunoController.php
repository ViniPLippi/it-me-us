<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Escola;
use App\Models\Estado;
use App\Models\Cidade;
use App\Models\Turma;
use App\Models\TurmaHasAluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Imports\AlunosImport;
use App\Exports\AlunosExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Storage;

class AlunoController extends Controller
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
        return view('alunos');
    }

    public function indexJSON()
    {
        $alunos = DB::table('alunos')
            ->select('alunos.*')
            ->orderBy('id')
            ->get();
        return Datatables::of($alunos)
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
        $estado = request('estado');
        $registro = 'required|digits:11|unique:alunos,alu_rge,NULL,id,alu_est_id';
        if (!is_null($estado)) {
            $registro .= ",{$estado}";
        }
        $regras = [
            'nome' => 'required|between:2,50',
            'registro' => $registro,
            'dataNasc' => 'required',
            'estado' => 'required',
        ];

        $mensagens = [
            'nome.required' => 'O campo Nome não pode estar em branco.',
            'registro.required' => 'O campo Registro não pode estar em branco.',
            'dataNasc.required' => 'Selecione uma Data de Nascimento.',
            'estado.required' => 'O campo Estado não pode estar em branco.',

            'nome.between' => 'O campo Nome precisa conter entre 2 até 50 caracteres.',
            'registro.digits' => 'O campo Registro precisar ter 11 números.',
            'registro.numeric' => 'O campo Registro precisar ter apenas números.',
            'registro.unique' => 'O Registro do Aluno informado, pertence a um aluno cadastrado para este Estado.',
            'dataNasc.date' => 'O campo precisa seguir o estilo de data informado',
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

        $aluno = new Aluno();
        $aluno->alu_nome = $request->input('nome');
        $aluno->alu_rge  = $request->input('registro');
        $aluno->alu_nasc = $request->input('dataNasc');
        $aluno->alu_est_id = $request->input('estado');
        $aluno->save();
        return json_encode($aluno);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show()
    {
        return view('importar');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $aluno = DB::table('alunos')
            ->join('estados', 'alunos.alu_est_id', '=', 'estados.id')
            ->select('alunos.*', 'estados.est_nome')
            ->where('alunos.id', '=', $id)
            ->get();
        if (isset($aluno)) {
            return json_encode($aluno);
        }
        return response('Aluno(a) não encontrado(a)!', 400);
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
        $aluno = Aluno::find($id);

        $mensagens = [
            'nome.required' => 'O campo Nome não pode estar em branco.',
            'registro.required' => 'O campo Registro não pode estar em branco.',
            'dataNasc.required' => 'Selecione uma Data de Nascimento.',
            'estado.required' => 'O campo Estado não pode estar em branco.',

            'nome.between' => 'O campo Nome precisa conter entre 2 até 50 caracteres.',
            'registro.digits' => 'O campo Registro precisar ter 11 números.',
            'registro.numeric' => 'O campo Registro precisar ter apenas números.',
            'registro.unique' => 'O Registro do Aluno informado, pertence a um aluno cadastrado.',
            'dataNasc.date' => 'O campo precisa seguir o estilo de data informado',
        ];

        $validator = Validator::make($request->all(), [
            'nome' => 'required|between:2,50',
            'registro' => "required|digits:11|unique:alunos,alu_rge,{$id},id",
            'dataNasc' => 'required',
            'estado' => 'required',
        ], $mensagens);

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

        if (isset($aluno)) {
            $aluno->alu_nome = $request->input('nome');
            $aluno->alu_rge  = $request->input('registro');
            $aluno->alu_nasc = $request->input('dataNasc');
            $aluno->alu_est_id = $request->input('estado');
            $aluno->save();
            return json_encode($aluno);
        }
        return response('Aluno não encontrado!', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $aluno = Aluno::find($id);
        if (isset($aluno)) {
            $aluno->delete();
        }
    }

    public function fileImportView()
    {
        return view('importar');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function fileImport(Request $request)
    {
        $regras['id'] = 'required';
        $regras['arquivo'] = 'required|max:2000';
        $mensagens['id.required'] = 'O Código Inep não pode estar em branco.';
        $mensagens['id.numeric'] = 'O Código Inep só pode conter números.';
        $mensagens['arquivo.required'] = 'Necessário enviar Planilha Excel.';
        $mensagens['arquivo.max'] = 'Arquivo muito grande! Máximo 2Mb.';

        $v = Validator::make($request->all(), $regras, $mensagens);

        if ($v->fails()) {

            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $v->getMessageBag()->toArray()
            ), 422);
        }

        $array = Excel::toArray([], request()->file('arquivo'));

        $cabec = "inep,rge,nome,nasc,uf,id_turma,status,obs";
        $cabecimport = "";

        foreach ($array[0][0] as $value) {
            $cabecimport .= $value . ",";
        }

        $cabecimport = substr($cabecimport, 0, 41);

        $validator = Validator::make([], []);

        if ($cabec != $cabecimport) {
            $validator->errors()->add('cabeçalho', 'Cabeçalho da Planilha Inválido!');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $escola = Escola::find($request->id);
        $erros = 0;

        for ($x = 1; $x < count($array[0]); $x++) {

            $inep = $array[0][$x][0];

            if ($inep != $escola->esc_inep) {
                $array[0][$x][6] .= "Não importado";
                $array[0][$x][7] .= "Código INEP diferente do informado.";
                $erros++;
                continue;
            }

            $rge = $array[0][$x][1];

            if (!isset($rge) or strlen($rge) == 0) {
                $array[0][$x][6] .= "Não importado";
                $array[0][$x][7] .= "RGE inválido ou não informado.";
                $erros++;
                continue;
            }

            $nome = $array[0][$x][2];

            if (!isset($nome) or strlen($nome) == 0) {
                $array[0][$x][6] .= "Não importado";
                $array[0][$x][7] .= "Nome não informado.";
                $erros++;
                continue;
            }

            $nasc = $array[0][$x][3];

            if (!isset($nasc) or strlen($nasc) == 0 or !is_numeric($nasc)) {
                $array[0][$x][6] .= "Não importado";
                $array[0][$x][7] .= "Data de Nascimento Inválida ou não informada.";
                $erros++;
                continue;
            } else {
                $nasc = Date::excelToDateTimeObject($nasc)->format('Y-m-d');
            }

            $data = explode('-', $nasc);

            if (!checkdate($data[1], $data[2], $data[0])) {
                $array[0][$x][6] .= "Não importado";
                $array[0][$x][7] .= "Data de Nascimento Inválida.";
                $erros++;
                continue;
            }

            $uf = strtoupper($array[0][$x][4]);

            if (!isset($uf) or strlen($nasc) == 0) {
                $array[0][$x][6] .= "Não importado";
                $array[0][$x][7] .= "UF Inválida ou não informada.";
                $erros++;
                continue;
            }

            $estado = Estado::where('est_sigla', '=', $uf)->first();

            if ($estado == null) {
                $array[0][$x][6] .= "Não importado";
                $array[0][$x][7] .= "UF Inválida.";
                $erros++;
                continue;
            }

            $cidade = Cidade::find($escola->esc_cid_id);

            if ($cidade->cid_est_id != $estado->id) {
                $array[0][$x][6] .= "Não importado";
                $array[0][$x][7] .= "UF Diferente da Escola Informada.";
                $erros++;
                continue;
            }

            $idturma = $array[0][$x][5];

            $turma = Turma::find($idturma);

            if ($turma == null) {
                $array[0][$x][6] .= "Não importado";
                $array[0][$x][7] .= "ID Turma não cadastrada.";
                $erros++;
                continue;
            }

            if ($turma->tur_esc_id != $escola->id) {
                $array[0][$x][6] .= "Não importado";
                $array[0][$x][7] .= "Turma não pertence à Escola informada.";
                $erros++;
                continue;
            }

            $aluno = Aluno::where("alu_rge","=",$rge)->where("alu_est_id","=",$estado->id)->first();

            if($aluno==null){
                $aluno = new Aluno();
                $aluno->alu_nome = $nome;
                $aluno->alu_rge = $rge;
                $aluno->alu_nasc = $nasc;
                $aluno->alu_est_id = $estado->id;
                $aluno->save();
                $turmaha = new TurmaHasAluno();
                $turmaha->tha_tur_id = $turma->id;
                $turmaha->tha_alu_id = $aluno->id;
                $turmaha->save();
                $array[0][$x][6] .= "Importado";
                $array[0][$x][7] .= "Cadastro criado e inserido na Turma.";
            }else{
                $array[0][$x][6] .= "Importado";
                $array[0][$x][7] .= "Aluno já Cadastrado.";
                $turmaha = TurmaHasAluno::where("tha_tur_id","=",$turma->id)->where("tha_alu_id","=",$aluno->id)->first();
                if($turmaha!=null){
                    $array[0][$x][6] = "Não Importado";
                    $array[0][$x][7] .= PHP_EOL."Aluno já alocado na turma.";
                    $erros++;
                }else{
                    $erro=0;
                    $turmahas = Turma::where("tur_esc_id","=",$escola->id)
                        ->where("tur_ano","=",$turma->tur_ano)
                        ->where("tur_serie","=",$turma->tur_serie)
                        ->where("id","!=",$turma->id)->get();
                    if($turmahas!=null){
                        foreach ($turmahas as $turmaaha) {
                            $turmaha = TurmaHasAluno::where("tha_tur_id","=",$turmaaha->id)->where("tha_alu_id","=",$aluno->id)->first();
                            if($turmaha!=null){
                                $array[0][$x][6] = "Não Importado";
                                $array[0][$x][7] .= PHP_EOL."Aluno já alocado em outra turma na mesma escola/ano calendário/ano.";
                                $erros++;
                                $erro++;
                                break;
                            }
                        }
                    }
                    if($erro==0){
                        $turmaha = new TurmaHasAluno();
                        $turmaha->tha_tur_id = $turma->id;
                        $turmaha->tha_alu_id = $aluno->id;
                        $turmaha->save();
                        $array[0][$x][7] .= "Inserido na Turma.";
                    }
                }
            }
        }

        $export = new AlunosExport($array);

        $filenameWithExt = $request->file('arquivo')->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('arquivo')->getClientOriginalExtension();
        $time = time();
        $fileNameToStore = 'public/exports/'.$escola->esc_inep.'/Importar_Alunos_' . $time . '.' . $extension;

        Excel::store($export, $fileNameToStore);

        return response()->json(array(
            'success' => true,
            'message' => 'Planilha Processada com Sucesso!',
            'data' => ['file' => 'Importar_Alunos_' . $time . '.' . $extension,
                        'id' => $time,
                        'inep' => $escola->esc_inep,
                        'numerros' => $erros]
        ), 200);
    }

    public function download($id,$inep)
    {
        $file = "/public/storage/exports//".$inep."//Importar_Alunos_".$id.".xlsx";

        if (isset($id)) {
            return Storage::download($file);
        }

        return;
    }
}
