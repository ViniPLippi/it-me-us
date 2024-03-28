@extends('layouts.principal', ['current' => 'login'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-10">
                <div class="divMainImage">
                    <img src="{{ asset('img/background-kid.png') }}" width="100%" />
                </div>
            </div>
            <div class="col-md-2"> <!-- -ok -->
                <div class="cardLogin mt-2 ml-2" ><!-- -ok -->
                    <img class="imgGovBrCardLogin" src="{{ asset('img/Gov.br_logo.png') }}" alt="Logo Gov.br"> <!-- -ok -->
                    <h5 class="textFont textAuthLabel">Autenticação</h5> <!-- -ok -->
                    <div class="card-body btnLoginAlign ">
                        <form action="{{route('logingovbr')}}">
                            <div class=" form-group row">
                                <button class="btnLogin textFontLogin" type="submit">
                                Entrar com&nbsp;
                                    <img src="{{ asset('img/Gov.br_logo.png') }}" id="BtnLoginGovBr"
                                        alt="Botão entrar gov.br" />
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        var ret;
        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#formLogin").submit(function(event) {
            event.preventDefault();
            if ($("#id").val() != '') {
                //                salvarAluno();
            } else {
                //                criarAluno();
            }
        });

        function loginGovBr() {
            let url = '{{ route('logingovbr') }}';
            $.get(url, function(data) {
                console.log(data);
            });
        }

        function loadLoginGovBrCallback() {
            let url = '{{ route('callback') }}';
            $.get(url, function(data) {
                console.log(data);
            });
        }

        $(function() {});
    </script>
@endsection
