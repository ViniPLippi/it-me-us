<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/teste/{cpf}',[App\Http\Controllers\TesteController::class, 'indexAPIJson']);
Route::post('/arquivos',[App\Http\Controllers\AlunoHasTesteController::class, 'indexAPIArquivos'])->name('enviarArquivos');
//Route::get('/logingovbr', [App\Http\Controllers\Auth\LoginGovBrController::class, 'index'])->name('logingovbr');
//Route::middleware('guest')->get('/logingovbr')->uses([App\Http\Controllers\Auth\LoginGovBrController::class, 'index'])->name('logingovbr');
//Route::get('/logingovbr', array('before' => 'auth', 'uses' => [App\Http\Controllers\Auth\LoginGovBrController::class, 'index']))->name('logingovbr');
//Route::get('/auth/redirect', function(){
//    info("Passou aqui...");
//    return Socialite::driver('govbr')->redirect();
//})->name('logingovbr')->withoutMiddleware(['auth']);
//Route::get('/auth/callback',function(){
//    $user = Socialite::driver('govbr')->user();
/*    $user = User::updateOrCreate([
        'govbr_id' => $govbr->id,
    ], [
        'name' => $githubUser->name,
        'email' => $githubUser->email,
        'github_token' => $githubUser->token,
        'github_refresh_token' => $githubUser->refreshToken,
    ]);

    Auth::login($user);
*/
//    return redirect('/home');
//});
