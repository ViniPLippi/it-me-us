<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laminas\Diactoros\RequestFactory;
use Laminas\Diactoros\ResponseFactory;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Log;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use AuthenticatesUsers;

class LoginGovBrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
//        info("Passou aqui...0");
        $this->middleware('auth', ['except' => ['redirect', 'Callback']]);
        $this->middleware('guest', ['except' => ['redirect', 'Callback']]);
    }

    public function authenticate($cpf, $password)
    {
        if (Auth::attempt(['email' => $cpf, 'password' => $password])) {
            return redirect()->intended('home');
        }
    }

    public function index()
    {
        //        return Socialite::driver('govbr')->redirect();
    }

    public function Callback(Request $request)
    {
 //       info("Passou aqui...3");
 //       info($request);

        $URL_PROVIDER = "https://sso.staging.acesso.gov.br";
        $CLIENT_ID = env('GOV_BR_CLIENT_ID');
        $SECRET = env('GOV_BR_SECRET');
        $REDIRECT_URI = env('GOV_BR_REDIRECT_URI');
        $SCOPE = "openid+email+phone+profile+govbr_empresa";
        $URL_SERVICOS = "https://api.staging.acesso.gov.br";
        $CODE = $request->input('GET_/?code');
        $STATE = $request->state;

        function processToClaims($token, $jwk)
        {
            $modulus = JWT::urlsafeB64Decode($jwk['keys'][0]['n']);
            $publicExponent = JWT::urlsafeB64Decode($jwk['keys'][0]['e']);

            $components = [
                'modulus' => pack('Ca*a*', 2, encodeLength(strlen($modulus)), $modulus),
                'publicExponent' => pack('Ca*a*', 2, encodeLength(strlen($publicExponent)), $publicExponent),
            ];
            $RSAPublicKey = pack('Ca*a*a*', 48, encodeLength(strlen($components['modulus']) + strlen($components['publicExponent'])), $components['modulus'], $components['publicExponent']);
            $rsaOID = pack('H*', '300d06092a864886f70d0101010500'); // hex version of MA0GCSqGSIb3DQEBAQUA
            $RSAPublicKey = chr(0) . $RSAPublicKey;
            $RSAPublicKey = chr(3) . encodeLength(strlen($RSAPublicKey)) . $RSAPublicKey;
            $RSAPublicKey = pack('Ca*a*', 48, encodeLength(strlen($rsaOID . $RSAPublicKey)), $rsaOID . $RSAPublicKey);
            $RSAPublicKey = "-----BEGIN PUBLIC KEY-----\r\n" . chunk_split(base64_encode($RSAPublicKey), 64) . '-----END PUBLIC KEY-----';
            JWT::$leeway = 3 * 60; //em segundos
            info("process...1");
            //            $decoded = JWT::decode($token, $RSAPublicKey, ['RS256']);
            $decoded = JWT::decode($token, new Key($RSAPublicKey, 'RS256'));
   //         info("process...2");
            return (array) $decoded;
        }

        function encodeLength($length)
        {
            if ($length <= 0x7f) {
                return chr($length);
            }
            $temp = ltrim(pack('N', $length), chr(0));
            return pack('Ca*', 0x80 | strlen($temp), $temp);
        }

        if (isset($CODE)) {
 //           info("Passou aqui...4");
            $fields = array(
                'grant_type' => urlencode('authorization_code'),
                'code' => urlencode($CODE),
                'redirect_uri' => urlencode($REDIRECT_URI)
            );
            $fields_string = "";
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $ch_token = curl_init();
            curl_setopt($ch_token, CURLOPT_URL, $URL_PROVIDER . "/token");
            curl_setopt($ch_token, CURLOPT_POST, count($fields));
            curl_setopt($ch_token, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch_token, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_token, CURLOPT_SSL_VERIFYPEER, true);
            $headers = array(
                'Content-Type:application/x-www-form-urlencoded',
                'Authorization: Basic ' . base64_encode($CLIENT_ID . ":" . $SECRET)
            );
            curl_setopt($ch_token, CURLOPT_HTTPHEADER, $headers);
            $json_output_tokens = json_decode(curl_exec($ch_token), true);
            curl_close($ch_token);
//            info("Passou aqui... 5");
//            info($json_output_tokens);

            $url = $URL_PROVIDER . '/jwk';
            $ch_jwk = curl_init();
            curl_setopt($ch_jwk, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch_jwk, CURLOPT_URL, $url);
            curl_setopt($ch_jwk, CURLOPT_RETURNTRANSFER, true);
//            info("Passou aqui...6");
            $json_output_jwk = json_decode(curl_exec($ch_jwk), true);
//            info("Passou aqui...7");
            curl_close($ch_jwk);

            $access_token = $json_output_tokens['access_token'];

            info($access_token);
            info($json_output_jwk);

            try {
                $json_output_payload_access_token = processToClaims($access_token, $json_output_jwk);
//                info("Passou aqui...8");
//                info($json_output_payload_access_token);
            } catch (\Exception $e) {
                $detalhamentoErro = $e;
                info($detalhamentoErro);
            }

//            info("Passou aqui...9");

            $id_token = $json_output_tokens['id_token'];

//            info("Passou aqui...10");
            info($id_token);

            try {
                $json_output_payload_id_token = processToClaims($id_token, $json_output_jwk);
                info("Passou aqui...11");
                info($json_output_payload_id_token);
                //                info($json_output_payload_id_token);
                //                info($json_output_payload_id_token['kid']);
                //                info($json_output_payload_id_token['sub']); //CPF do usuário autenticado.
                //                info($json_output_payload_id_token['name']); //Nome Completo do cadastro feito pelo usuário no Gov.br.
                //                info($json_output_payload_id_token['phone_number_verified']); //Confirma se o telefone foi validado no cadastro do Gov.br. Poderá ter o valor "true" ou "false"
                //                info($json_output_payload_id_token['phone_number']); // Número de telefone cadastrado no Gov.br do usuário autenticado. Caso o atributo phone_number_verified do ID_TOKEN tiver o valor false, o atributo phone_number não virá no ID_TOKEN)
                //                info($json_output_payload_id_token['email_verified']); // Confirma se o email foi validado no cadastro do Gov.br. Poderá ter o valor "true" ou "false")
                //                info($json_output_payload_id_token['email']); // Endereço de e-mail cadastrado no Gov.br do usuário autenticado. Caso o atributo email_verified do ID_TOKEN tiver o valor false, o atributo email não virá no ID_TOKEN
                //                info($json_output_payload_id_token['amr']); // Fator de autenticação do usuário. Pode ser “passwd” se o mesmo logou fornecendo a senha, ou “x509” se o mesmo utilizou certificado digital ou certificado em nuvem.

                $user = User::where('cpf', '=', $json_output_payload_id_token['sub'])->first();

                info("Passou aqui...12");

                info($user);

                info('CPF: '.$user->cpf);

                if (!empty($user)) {
                    return redirect()->route('autenticar',['cpf' => $user->cpf, 'password' => '12345678']);
                } else {
                    info('Usuário não cadastrado...');
                    return redirect('login');
                }
            } catch (\Exception $e) {
                $detalhamentoErro = $e;
                info($detalhamentoErro);
            }
        } else {
            info("Passou aqui...5");
            return redirect('login');
        }
    }

    public function redirect()
    {
        info("Passou aqui...1");

        //        require __DIR__ . '/vendor/autoload.php';

        $URL_PROVIDER = "https://sso.staging.acesso.gov.br";
        $CLIENT_ID = env('GOV_BR_CLIENT_ID');
        $SECRET = env('GOV_BR_SECRET');
        $REDIRECT_URI = env('GOV_BR_REDIRECT_URI');
        $SCOPE = "openid+email+phone+profile+govbr_empresa";
        $URL_SERVICOS = "https://api.staging.acesso.gov.br";

        function getRandomHex($num_bytes = 4)
        {
            return bin2hex(openssl_random_pseudo_bytes($num_bytes));
        }

        $uri = $URL_PROVIDER . "/authorize?response_type=code"
            . "&client_id=" . $CLIENT_ID
            . "&scope=" . $SCOPE
            . "&redirect_uri=" . urlencode($REDIRECT_URI)
            . "&nonce=" . getRandomHex()
            . "&state=" . getRandomHex();

        return redirect($uri);
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
