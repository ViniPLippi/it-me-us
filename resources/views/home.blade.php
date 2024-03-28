@extends('layouts.principal',["current" => "home"])

@section('content')

    <div class="divMainContainer">
        <?php
            $tipo = -1;
            if (Auth::user() != null){
                $tipo = Auth::user()->type;
            }
        ?>
         <div class="divMainImage">
            <img src="{{ asset('img/background-kid.png') }}" id="imgBackHome" />
        </div>

        
       <!--  <div class="divLoginSide">
            <p>Identifique-se no gov.br com:</p>

            <div><img src="" alt=""><p>Número do CPF</p></div>
            Digite seu CPF para criar ou acessar sua conta gov.br
            <p>CPF</p>
            <input placeholder="Digite seu CPF"/>
            </br>
            <button type="submit" class="btn btn-primary">Continuar</button>
            </br>
            <p>Outras opções de identificação:</p>
            <div> </div>
            
            <div><img src="" alt=""><p> Login com seu banco SUA CONTA SERÁ PRATA</p></div>
            <div><img src="" alt=""><p>Seu aplicativo gov.br</p></div>
            <div><img src="" alt=""><p>Seu certificado digital em nuvem</p></div>
           
            <div><img src="" alt=""><p>Entenda a conta gov.br</p></div>
            
        </div> -->


    </div>



@endsection
