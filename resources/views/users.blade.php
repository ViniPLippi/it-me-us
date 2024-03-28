@extends('layouts.principal', ['current' => 'user'])
@section('content')
    <style>
        .load {
            width: 100px;
            height: 100px;
            position: absolute;
            top: 30%;
            left: 45%;
            color: black;
            background-color: white;
            text-align: center;
            /* Centraliza o texto */
            z-index: 1000;
            /* Faz com que fique sobre todos os elementos da página */
            border-width: 2px;
            border-color: black;
            border-style: solid;
            display: inline-block;
            border-radius: 20px;
        }

        .aguarde {
            color: blue;
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="card border tableProperties">
        <div class="card-header sectionTableHead">
            <h4><b class="textFont">Lista de Usuários</b></h4>
        </div>
        <input type="hidden" id="tipouser" name="tipouser" class="form-control" value="{{ Auth::user()->type }}">
        <div class="card-body">
            <h5 class="card-title textFont" id="cardTitle"></h5>
            <table id="tabelaUsuarios" class="table table-striped textFont" style="width:100%">
                <thead class="tableHead">
                    <tr>
                        <th class="textFont">Nome</th>
                        <th class="textFont">CPF</th>
                        <th class="textFont">Tipo</th>
                        <th class="textFont">Email</th>
                        <th class="textFont"><span class="colActions" >Ações</span></th>
                    </tr>
                </thead>
                <tbody class="tabelaBody tBody">
                </tbody>
                <tfoot>
                    <tr>
                        <th class="textFont">Nome</th>
                        <th class="textFont" >CPF</th>
                        <th class="textFont" >Tipo</th>
                        <th class="textFont" >Email</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer sectionTableFooter">
            <button class="btn btn-sm btn-primary btnGOV textFont" id="novoUsuario" role="button" onClick="novoUsuario()">Novo Usuário</a>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="dlgUsuarios">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formAluno" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header textFont" id="cabecform">
                        <h5 class="modal-title textFont">Novo Usuário</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" class="form-control textFont">

                        <div class="form-group">
                            <label for="nome" class="control-label textFont">Nome do Usuário</label>
                            <div class="input-group">
                                <input type="text" class="form-control textFont" id="nome" name="nome"
                                    placeholder="Nome do Usuários">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cpf" class="control-label textFont" >CPF</label>
                            <div class="input-group">
                                <input type="number" class="form-control textFont" id="cpf" name="cpf" placeholder="CPF" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tipo" class="control-label textFont">Tipo de Usuários</label>
                            <div class="input-group">
                                <select class="form-control textFont" id="tipoUsuario" name="tipo" placeholder="Selecione um Tipo" required>
                                    <option value="" selected>Selecione um Tipo</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="control-label textFont">Email</label>
                            <div class="input-group">
                                <input type="email" class="form-control textFont" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="control-label textFont">Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control textFont" id="password" name="password" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="salvar" class="btn btn-primary textFont">Salvar</button>
                        <button hidden id="update" type="submit" class="btn btn-primary textFont">Atualizar</button>
                        <button type="cancel" class="btn btn-danger textFont" data-dismiss="modal">Cancelar</button>
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger textFont" role="alert">
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                </form>
            </div>
            @if (Session::has('errors'))
                <script>
                    $(document).ready(function() {
                        $('#dlgUsuarios').modal({
                            show: true
                        });
                    })
                </script>
            @endif
        </div>
    </div>
@endsection
<!-------------------- Código Javascript --------------------------->

@section('javascript')
    <script type="text/javascript">
        var ret;
        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        tiposUsuarios = [
                {
                    'tipo': '01',
                    'correspondente': 'Super admin MEC'
                },
                {
                    'tipo': '02',
                    'correspondente': 'Admin MEC'
                },
                {
                    'tipo': '03',
                    'correspondente': 'Usuário MEC'
                },
                {
                    'tipo': '11',
                    'correspondente': 'Admin Sec.Estadual'
                },
                {
                    'tipo': '12',
                    'correspondente': 'Usuário Sec.Estadual'
                },
                {
                    'tipo': '21',
                    'correspondente': 'Admin Sec. Municipal'
                },
                {
                    'tipo': '22',
                    'correspondente': 'Usuário Sec. Municipal'
                },
                {
                    'tipo': '31',
                    'correspondente': 'Admin Escola'
                },
                {
                    'tipo': '32',
                    'correspondente': 'Diretor Escola'
                },
                {
                    'tipo': '33',
                    'correspondente': 'Usuário Escola'
                },
                {
                    'tipo': '41',
                    'correspondente': 'Professor Alfabetizador'
                },
                {
                    'tipo': '42',
                    'correspondente': 'Professor Aplicador'
                }
            ]

        function formataTipoUsuario(tipoUsuario){
            for(let i=0; i < tiposUsuarios.length; i++){
                _tipoUsuario = tiposUsuarios[i];
                if(_tipoUsuario['tipo'] === tipoUsuario){
                    return _tipoUsuario['correspondente'];
                }
            }
            return "Tipo não identificado";
        }

        function formataCPF(cpf){
            return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
        }

        function novoUsuario() {
            document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Novo Usuário</h5>';
            $('#nome').val('');
            $('#cpf').val('');
            $('#tipo').val('');
            $('#email').val('');
            $('#password').val('');

            $('#dlgUsuarios').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        }

        // $("#dlgUsuarios").submit(function(event) {
        //     event.preventDefault();
        //     console.log(event);
        //     criarUsuario();
        //     $("#dlgUsuarios").modal('hide');
        // });

        $("#dlgUsuarios #salvar").click(function(event){
            event.preventDefault();
            criarUsuario();
            $("#dlgUsuarios").modal('hide');
        });

        $("#dlgUsuarios #update").click(function(event){

            event.preventDefault();

            salvarUsuario();

            $('#update').attr("hidden",true);
            $('#salvar').attr("hidden",false);
            $("#dlgUsuarios").modal('hide');
        });

        function criarUsuario() {
            var select = document.getElementById('tipoUsuario');
            usuario = {
                nome: $("#nome").val(),
                cpf: $("#cpf").val(),
                email: $("#email").val(),
                tipo: select.options[select.selectedIndex].value,
                password: $('#password').val()
            };
            let url = '{{route('user.store')}}';
            $.ajaxSetup({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(url, usuario, function(data) {
                usuario = JSON.parse(data);
                concole.log(usuario)
                var html = '<button type="button" class="btn btn-sm btn-primary textFont" onclick="editar(' + usuario.id +
                    ')">Editar</button>' + ' ';
                html += '<button type="button" class="btn btn-sm btn-danger textFont" onclick="excluir(' + usuario.id +
                    ')">Excluir</button>';
                var t = $('#tabelaUsuarios').DataTable();

                t.row.add({
                    "tipo": usuario.tipo,
                    "nome": usuario.nome,
                    "cpf": usuario.cpf,
                    "email": usuario.email,
                    "action": html
                }).draw(false);
            }).done(function() {
                Swal.fire('Usuario(a) Cadastrado(a) com Sucesso!', '', 'success');
            }).fail(function(jqXhr, json, errorThrown) { // this are default for ajax errors
                var errors = jqXhr.responseJSON;
                var errorsHtml = '';
                $.each(errors['errors'], function(index, value) {
                    errorsHtml += '<ul class="list-group"><li class="list-group-item alert alert-danger textFont">' + value + '</li></ul>';
                });
                //I use SweetAlert2 for this
                const Toast = Swal.mixin({
                    title: "Error " + jqXhr.status + ': ' + errorThrown, // this will output "Error 422: Unprocessable Entity"
                    html: errorsHtml,
                    width: 'auto'
                }, function(isConfirm) {
                    if (isConfirm) {
                        $('#openModal').click(); //this is when the form is in a modal
                    }
                });
                Toast.fire({
                    icon: 'error',
                    title: 'Erros encontrados!'
                });
            });
        }

        function searchTypeUser(textUser, typeUser){
            for(let tipoUsuario in tiposUsuarios){
                if(tipoUsuario['correspondente'] == textUser){
                    return typeUser === tipoUsuario['tipo'];
                }
            }
            return false;
        }

        function editar(id) {

            let url = '{{route('user.edit',':queryId')}}';
            url = url.replace(':queryId', id);
            $.getJSON(url, function(data) {
                document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Editar Usuário</h5>';
                usuario = data;
                console.log(usuario);

                $('#id').val(usuario.id);
                $('#nome').val(usuario.name);
                $('#cpf').val(usuario.cpf);
                $('#email').val(usuario.email);
                $('#password').val('');

                $("#tipoUsuario option").filter(function() {
                    return searchTypeUser(this.text, usuario.tipo);
                }).attr('selected', true);

                $('#dlgUsuarios').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });

                $('#update').attr("hidden",false);
                $('#salvar').attr("hidden",true);
            });
        }

        function salvarUsuario() {
            var select = document.getElementById('tipoUsuario');
            usuario = {
                id: $("#id").val(),
                nome: $("#nome").val(),
                cpf: $("#cpf").val(),
                email: $('#email').val(),
                password: $('#password').val(),
                tipo: select.options[select.selectedIndex].value
            }

            let url = '{{route('user.update',':queryId')}}';
            url = url.replace(':queryId', usuario.id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'PUT',
                url: url,
                contentType: 'application/json',
                data: JSON.stringify(usuario),
            }).done(function() {
                var table = $('#tabelaUsuarios').DataTable();
                table.row($(this).parents('tr')).draw(false);
                Swal.fire('Usuário Atualizado com Sucesso!', '', 'success')
            }).fail(function(jqXhr, json, errorThrown) { // this are default for ajax errors
                var errors = jqXhr.responseJSON;
                var errorsHtml = '';
                $.each(errors['errors'], function(index, value) {
                    errorsHtml += '<ul class="list-group"><li class="list-group-item alert alert-danger textFont">' + value + '</li></ul>';
                });
                //I use SweetAlert2 for this
                const Toast = Swal.mixin({
                    title: "Error " + jqXhr.status + ': ' + errorThrown, // this will output "Error 422: Unprocessable Entity"
                    html: errorsHtml,
                    width: 'auto'
                }, function(isConfirm) {
                    if (isConfirm) {
                        $('#openModal').click(); //this is when the form is in a modal
                    }
                });
                Toast.fire({
                    icon: 'error',
                    title: 'Erros encontrados!'
                });
            });
        }

        function excluir(id) {
            Swal.fire({
                title: 'Confirma a exclusão desse(a) Usuário(a)?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não',
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '{{route('user.destroy',':queryId')}}';
                    url = url.replace(':queryId', id);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "DELETE",
                        url: url,
                        data: {
                            somefield: "Some field value",
                            _token: '{{ csrf_token() }}'
                        },
                        context: this,
                        success: function() {
                            var table = $('#tabelaUsuarios').DataTable();
                            table.row($(this).parents('tr')).remove().draw(false);
                            Swal.fire('Usuário excluído com sucesso!','info');
                        },
                        error: function(error) {
                            Swal.fire('Ocorreu um erro ao excluir:\n' + error,'error');
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire('Exclusão não foi realizada!', '', 'info');
                }
            })
        }

        function carregarTiposUsuarios() {
            for(let i = 0; i < tiposUsuarios.length; i++){
                tipoUsuario = tiposUsuarios[i]
                tipo = tipoUsuario['tipo']
                correspondente = tipoUsuario['correspondente']
                opcao = '<option value = "' + tipo + '">' + correspondente + '</option>';
                $('#tipoUsuario').append(opcao);
            }
            document.getElementById('tipoUsuario').value = -1;
        }

        $(function() {
            carregarTiposUsuarios();
            let url = '{{route('user.indexJSON')}}';

            $('#tabelaUsuarios').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": url,
                columnDefs: [
                    {
                        targets: 1,
                        render: function(cpf, type, row) {
                            return formataCPF(cpf);
                        },
                    },
                    {
                        targets: 2,
                        render: function(tipoUsuario, type, row) {
                            return formataTipoUsuario(tipoUsuario);
                        },
                    }
                ],
                columns: [{
                        data: "name"
                    },
                    {
                        data: "cpf"
                    },
                    {
                        data: "tipo"
                    },
                    {
                        data: "email"
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false
                    },
                ],
                language: {
                    "paginate": {
                        "previous": "<",
                        "next": ">"
                    },
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "Nada encontrado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "Nenhum registro disponível",
                    "infoFiltered": "(filtrado de _MAX_ registros no total)",
                    "search": "Buscar",
                    "decimal": ",",
                    "thousands": "."
                },
                initComplete: function() {
                    $('#tabelaUsuarios tfoot th').each(function() {
                        var title = $(this).text();
                        if (title === "Nome" || title === "CPF" || title ==
                            "Tipo") {
                            $(this).html('<input type="text" placeholder="Filtrar ' + title +
                                '" />');
                        }else{
                            $(this).html('');
                        }
                    });
                    // Apply the search
                    this.api().columns([0, 1, 2]).every(function() {
                        var that = this;

                        $('input', this.footer()).on('keydown', function(ev) {
                            if (ev.keyCode == 13) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
                }
            });
        });
    </script>
@endsection
