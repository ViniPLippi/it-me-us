<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AlunoHasTeste;

class AlunoHasTesteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('testeapiarq');
    }

    public function indexAPIArquivos(Request $request)
    {
        $validatedData = $request->validate([
            'files' => 'required',
            'files.*' => 'mimes:csv,txt,xlx,xls,pdf'
        ]);

        info($request->Parametro);

        $id = $request->id;

        if ($request->TotalFiles > 0) {

            $aht = AlunoHasTeste::find($id);

            for ($x = 0; $x < $request->TotalFiles; $x++) {

                if ($request->hasFile('files' . $x)) {
                    $file = $request->file('files' . $x);
                    $nome = $file->getClientOriginalName();
                    $ext = $file->getClientOriginalExtension();
                    $arquivo = $nome;
                    $path = $file->storeAs('public/documents', $arquivo);
                    if($x==0){
                        $aht->aht_arqaudio1 = $path;
                        $aht->aht_statusarq1 = 1; //0- Início / 1-Arquivo Recebido / 2-Arquivo Analisado
                    } elseif ($x==1){
                        $aht->aht_arqaudio2 = $path;
                        $aht->aht_statusarq2 = 1;
                    } else {
                        $aht->aht_arqaudio3 = $path;
                        $aht->aht_statusarq3 = 1;
                    }
                }
            }

            $aht->aht_status = 2; //0- Início / 1-Arquivos Recebidos Incompleto / 2-Arquivos Recebidos Completo
                                  //3- Arquivos Analisados Incompleto / 4-Arquivos Analisados Completo

            $aht->save();

            return response()->json(['success' => 'Ajax Multiple fIle has been uploaded']);
        } else {
            return response()->json(["message" => "Please try again."]);
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
        //
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
        //
    }
}
