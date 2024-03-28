<?php

use App\Http\Controllers\Auth\LoginGovBrController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function (Request $request) {
//});

if (Auth::check()) {
    Route::get('/', function () {
        return view('home');
    });
} else {
    Route::get('/', function (Request $request) {
        info("Passou aqui...-1");
        info($request);
        if (isset($request->code)){
            return redirect()->action([LoginGovBrController::class, 'Callback'], $request);
        };
        return view('auth/login');
    });
}

Route::get('/logingovbr',[App\Http\Controllers\Auth\LoginGovBrController::class,'redirect'])->name('logingovbr');

Route::get('/callback',[App\Http\Controllers\Auth\LoginGovBrController::class,'Callback'])->name('callback');

Route::get('/autenticar/{cpf}/{password}',[App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('autenticar');

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/estados',[App\Http\Controllers\EstadoController::class, 'indexView'])->name('estados.index');
Route::get('/estadosdt',[App\Http\Controllers\EstadoController::class, 'indexdt'])->name('estados.indexdt');
Route::get('/estadosJSON',[App\Http\Controllers\EstadoController::class, 'indexJSON'])->name('estados.indexJSON');
Route::get('/estadosdtJson',[App\Http\Controllers\EstadoController::class, 'indexdtJson'])->name('estados.indexdtJson');
Route::get('/estados/novo',[App\Http\Controllers\EstadoController::class, 'create'])->name('estados.create');
Route::post('/estados',[App\Http\Controllers\EstadoController::class, 'store'])->name('estados.store');
Route::delete('/estados/apagar/{id}',[App\Http\Controllers\EstadoController::class, 'destroy'])->name('estados.destroy');
Route::get('/estados/{id}',[App\Http\Controllers\EstadoController::class, 'edit'])->name('estados.edit');
Route::put('/estados/{id}',[App\Http\Controllers\EstadoController::class, 'update'])->name('estados.update');
Route::get('/estados/estadosform',[App\Http\Controllers\EstadoController::class, 'indexJson'])->name('estados.indexJson');
Route::get('/estados/sigla/{id}',[App\Http\Controllers\EstadoController::class, 'getSigla'])->name('estados.getSigla');
Route::get('/estadosa',[App\Http\Controllers\EstadoController::class, 'indexJsonA'])->name('estados.indexJsonA');

Route::get('/cidades',[App\Http\Controllers\CidadeController::class, 'index'])->name('cidades.index');
Route::get('/cidadesJson',[App\Http\Controllers\CidadeController::class, 'indexJSON'])->name('cidades.indexJSON');
Route::get('/cidades/novo',[App\Http\Controllers\CidadeController::class, 'create'])->name('cidades.create');
Route::post('/cidades',[App\Http\Controllers\CidadeController::class, 'store'])->name('cidades.store');
Route::delete('/cidades/apagar/{id}',[App\Http\Controllers\CidadeController::class, 'destroy'])->name('cidades.destroy');
Route::get('/cidades/{id}',[App\Http\Controllers\CidadeController::class, 'edit'])->name('cidades.edit');
Route::put('/cidades/{id}',[App\Http\Controllers\CidadeController::class, 'update'])->name('cidades.update');
Route::get('/cidades/estadosform',[App\Http\Controllers\CidadeController::class, 'indexJson'])->name('cidades.estadosform');
Route::get('/cidades/estadosuf/{id}',[App\Http\Controllers\CidadeController::class, 'indexUF'])->name('cidades.estadosuf');

Route::get('/secretarias', [App\Http\Controllers\SecretariaController::class, 'index'])->name('secretarias.index');
Route::get('/secretariasJson', [App\Http\Controllers\SecretariaController::class, 'indexJSON'])->name('secretarias.indexJSON');
Route::get('/secretarias/novo', [App\Http\Controllers\SecretariaController::class, 'create'])->name('secretarias.create');
Route::post('/secretarias', [App\Http\Controllers\SecretariaController::class, 'store'])->name('secretarias.store');
Route::delete('/secretarias/apagar/{id}', [App\Http\Controllers\SecretariaController::class, 'destroy'])->name('secretarias.destroy');
Route::get('/secretarias/{id}', [App\Http\Controllers\SecretariaController::class, 'edit'])->name('secretarias.edit');
Route::put('/secretarias/{id}', [App\Http\Controllers\SecretariaController::class, 'update'])->name('secretarias.update');
Route::get('/secretarias/secretariasform', [App\Http\Controllers\SecretariaController::class, 'indexJson'])->name('secretarias.secretariasform');

Route::get('/escolas',[App\Http\Controllers\EscolaController::class, 'index'])->name('escolas.index');
Route::get('/escolasJson/{id}',[App\Http\Controllers\EscolaController::class, 'indexJSON'])->name('escolas.indexJSON');
Route::get('/escolas/novo',[App\Http\Controllers\EscolaController::class, 'create'])->name('escolas.create');
Route::post('/escolas',[App\Http\Controllers\EscolaController::class, 'store'])->name('escolas.store');
Route::delete('/escolas/apagar/{id}',[App\Http\Controllers\EscolaController::class, 'destroy'])->name('escolas.destroy');
Route::get('/escolas/{id}',[App\Http\Controllers\EscolaController::class, 'edit'])->name('escolas.edit');
Route::put('/escolas/{id}',[App\Http\Controllers\EscolaController::class, 'update'])->name('escolas.update');
Route::get('/escolas/estadosform',[App\Http\Controllers\EscolaController::class, 'indexJson'])->name('escolas.indexJson');
Route::get('/escolasINEP/{id}',[App\Http\Controllers\EscolaController::class, 'indexINEP'])->name('escolas.indexINEP');
Route::get('/escolas/cidade/{id}',[App\Http\Controllers\EscolaController::class, 'indexCidade'])->name('escolas.indexcidade');

Route::get('/alunos',[App\Http\Controllers\AlunoController::class, 'index'])->name('alunos.index');
Route::get('/alunosJson',[App\Http\Controllers\AlunoController::class, 'indexJSON'])->name('alunos.indexJSON');
Route::get('/alunos/novo',[App\Http\Controllers\AlunoController::class, 'create'])->name('alunos.create');
Route::post('/alunos',[App\Http\Controllers\AlunoController::class, 'store'])->name('alunos.store');
Route::delete('/alunos/apagar/{id}',[App\Http\Controllers\AlunoController::class, 'destroy'])->name('alunos.destroy');
Route::get('/alunosfileimportview', [App\Http\Controllers\AlunoController::class, 'fileImportView'])->name('alunos.fileimportview');
Route::post('/alunosfileimport', [App\Http\Controllers\AlunoController::class, 'fileImport'])->name('alunos.fileimport');
Route::get('/alunos/{id}',[App\Http\Controllers\AlunoController::class, 'edit'])->name('alunos.edit');
Route::put('/alunos/{id}',[App\Http\Controllers\AlunoController::class, 'update'])->name('alunos.update');
Route::get('/alunos/alunosform',[App\Http\Controllers\AlunoController::class, 'indexJson'])->name('alunos.indexJson');
Route::get('/alunos/download/{id}/{inep}',[App\Http\Controllers\AlunoController::class, 'download'])->name('alunos.download');

Route::get('/turmas',[App\Http\Controllers\TurmaController::class, 'index'])->name('turmas.index');
Route::get('/turmasJson/{id}',[App\Http\Controllers\TurmaController::class, 'indexJSON'])->name('turmas.indexJSON');
Route::get('/turmas/novo',[App\Http\Controllers\TurmaController::class, 'create'])->name('turmas.create');
Route::post('/turmas',[App\Http\Controllers\TurmaController::class, 'store'])->name('turmas.store');
Route::delete('/turmas/apagar/{id}',[App\Http\Controllers\TurmaController::class, 'destroy'])->name('turmas.destroy');
Route::get('/turmas/{id}',[App\Http\Controllers\TurmaController::class, 'edit'])->name('turmas.edit');
Route::put('/turmas/{id}',[App\Http\Controllers\TurmaController::class, 'update'])->name('turmas.update');
Route::get('/turmas/estadosform',[App\Http\Controllers\TurmaController::class, 'indexJson'])->name('turmas.indexJson');
Route::get('/turmas/user/{cpf}',[App\Http\Controllers\TurmaController::class, 'indexUser'])->name('turmas.indexUser');
Route::get('/turmas/aluno/{rge}',[App\Http\Controllers\TurmaController::class, 'indexAluno'])->name('turmas.indexAluno');

Route::get('/turmahasalunos',[App\Http\Controllers\TurmaHasAlunoController::class, 'index'])->name('turmahasalunos.index');
Route::get('/turmahasalunosJson/{id}',[App\Http\Controllers\TurmaHasAlunoController::class, 'indexJSON'])->name('turmahasalunos.indexJSON');
Route::get('/turmahasalunos/novo',[App\Http\Controllers\TurmaHasAlunoController::class, 'create'])->name('turmahasalunos.create');
Route::post('/turmahasalunos',[App\Http\Controllers\TurmaHasAlunoController::class, 'store'])->name('turmahasalunos.store');
Route::delete('/turmahasalunos/apagar/{id}',[App\Http\Controllers\TurmaHasAlunoController::class, 'destroy'])->name('turmahasalunos.destroy');
Route::get('/turmahasalunos/{id}',[App\Http\Controllers\TurmaHasAlunoController::class, 'edit'])->name('turmahasalunos.edit');
Route::put('/turmahasalunos/{id}',[App\Http\Controllers\TurmaHasAlunoController::class, 'update'])->name('turmahasalunos.update');
Route::get('/turmahasalunos/estadosform',[App\Http\Controllers\TurmaHasAlunoController::class, 'indexJson'])->name('turmahasalunos.indexJson');

Route::get('/testes',[App\Http\Controllers\TesteController::class, 'index'])->name('testes.index');
Route::get('/testesJson',[App\Http\Controllers\TesteController::class, 'indexJSON'])->name('testes.indexJSON');
Route::get('/testesf',[App\Http\Controllers\TesteController::class, 'indexJSONF'])->name('testes.indexJSONF');
Route::get('/testes/novo',[App\Http\Controllers\TesteController::class, 'create'])->name('testes.create');
Route::post('/testes',[App\Http\Controllers\TesteController::class, 'store'])->name('testes.store');
Route::delete('/testes/apagar/{id}',[App\Http\Controllers\TesteController::class, 'destroy'])->name('testes.destroy');
Route::get('/testes/{id}',[App\Http\Controllers\TesteController::class, 'edit'])->name('testes.edit');
Route::put('/testes/{id}',[App\Http\Controllers\TesteController::class, 'update'])->name('testes.update');
Route::get('/testes/estadosform',[App\Http\Controllers\TesteController::class, 'indexJson'])->name('testes.estadosform');
Route::get('/testessala',[App\Http\Controllers\TesteController::class, 'sala'])->name('testes.sala');

Route::get('/turmahasaplicadorhasteste',[App\Http\Controllers\TurmaHasAplicadorHasTesteController::class, 'index'])->name('turmahasaplicadorhasteste.index');
Route::get('/turmahasaplicadorhastesteJson/{id}',[App\Http\Controllers\TurmaHasAplicadorHasTesteController::class, 'indexJSON'])->name('turmahasaplicadorhasteste.indexJSON');
Route::get('/turmahasaplicadorhasteste/novo',[App\Http\Controllers\TurmaHasAplicadorHasTesteController::class, 'create'])->name('turmahasaplicadorhasteste.create');
Route::post('/turmahasaplicadorhasteste',[App\Http\Controllers\TurmaHasAplicadorHasTesteController::class, 'store'])->name('turmahasaplicadorhasteste.store');
Route::delete('/turmahasaplicadorhasteste/apagar/{id}',[App\Http\Controllers\TurmaHasAplicadorHasTesteController::class, 'destroy'])->name('turmahasaplicadorhasteste.destroy');
Route::get('/turmahasaplicadorhasteste/{id}',[App\Http\Controllers\TurmaHasAplicadorHasTesteController::class, 'edit'])->name('turmahasaplicadorhasteste.edit');
Route::put('/turmahasaplicadorhasteste/{id}',[App\Http\Controllers\TurmaHasAplicadorHasTesteController::class, 'update'])->name('turmahasaplicadorhasteste.update');
Route::get('/turmahasaplicadorhasteste/estadosform',[App\Http\Controllers\TurmaHasAplicadorHasTesteController::class, 'indexJson'])->name('turmahasaplicadorhasteste.indexJson');

Route::get('/user',[App\Http\Controllers\UserController::class, 'index'])->name('user.index');
Route::get('/userJson',[App\Http\Controllers\UserController::class, 'indexJSON'])->name('user.indexJSON');
Route::get('/user/novo',[App\Http\Controllers\UserController::class, 'create'])->name('user.create');
Route::post('/user',[App\Http\Controllers\UserController::class, 'store'])->name('user.store');
Route::delete('/user/apagar/{id}',[App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');
Route::get('/user/{id}',[App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');
Route::put('/user/{id}',[App\Http\Controllers\UserController::class, 'update'])->name('user.update');

Route::get('/testeapiarq',[App\Http\Controllers\AlunoHasTesteController::class, 'index'])->name('testeapiarq.index');

});
