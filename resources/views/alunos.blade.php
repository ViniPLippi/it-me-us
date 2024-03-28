@extends('layouts.principal', ['current' => 'alunos'])
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
            <h4><b class="textFont">Lista de Alunos</b></h4>
        </div>
        <input type="hidden" id="tipouser" name="tipouser" class="form-control" value="{{ Auth::user()->type }}">
        <div class="card-body">
            <h5 class="card-title textFont" id="cardTitle"></h5>
            <table id="tabelaAlunos" class="table table-striped textFont" style="width:100%">
                <thead class="tableHead">
                    <tr>
                        <th class="textFont">Código</th>
                        <th class="textFont">Nome</th>
                        <th class="textFont">Registro</th>
                        <th class="textFont">Data Nascimento</th>
                        <th class="textFont"><span class="colActions" >Ações</span></th>
                    </tr>
                </thead>
                <tbody class="tabelaBody tBody">
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th class="textFont">Nome</th>
                        <th class="textFont" >Registro</th>
                        <th class="textFont" >Data Nascimento</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer sectionTableFooter">
            <button class="btn btn-sm btn-primary btnGOV textFont" id="novoAluno" role="button" onClick="novoAluno()">Novo Aluno</a>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="dlgAlunos">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formAluno" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header textFont" id="cabecform">
                        <h5 class="modal-title textFont">Novo Aluno</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" class="form-control textFont">

                        <div class="form-group">
                            <label for="nome" class="control-label textFont">Nome do Aluno</label>
                            <div class="input-group">
                                <input type="text" class="form-control textFont" id="nome" name="nome"
                                    placeholder="EX: Evelyn da Silva Souza">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="registro" class="control-label textFont" >Registro do Aluno (11 dígitos)</label>
                            <div class="input-group">
                                <input type="number" class="form-control textFont" id="registro" name="registro" placeholder="EX: 25989145657" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dataNasc" class="control-label textFont">Data de Nascimento</label>
                            <div class="input-group">
                                <input type="date" class="form-control textFont" id="dataNasc" name="dataNasc" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="estado" class="control-label textFont">Estado</label>
                            <div class="input-group">
                                <select class="form-control textFont" id="estado" name="estado" placeholder="Selecione um Estado" required>
                                    <option value="" selected>Selecione um Estado</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary textFont">Salvar</button>
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
                        $('#dlgAlunos').modal({
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

        function formataData(data) {
            var dataiso = new Date(data);
            var dataFormatada = adicionaZero(dataiso.getDate()+1) + "/" + adicionaZero(dataiso.getMonth() + 1) + "/" + dataiso
                .getFullYear();
            return dataFormatada;
        }


        function adicionaZero(numero) {
            if (numero <= 9)
                return "0" + numero;
            else
                return numero;
        }

        function novoAluno() {
            document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Novo Aluno</h5>';
            $('#id').val('');
            $('#nome').val('');
            $('#registro').val('');
            $('#dataNasc').val('');
            $('#estado').val('');
            $('#dlgAlunos').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        }

        $("#dlgAlunos").submit(function(event) {
            event.preventDefault();
            if ($("#id").val() != '') {
                salvarAluno();
            } else {
                criarAluno();
            }
            $("#dlgAlunos").modal('hide');
        });

        function criarAluno() {
            var select = document.getElementById('estado');
            aluno = {
                nome: $("#nome").val(),
                registro: $("#registro").val(),
                dataNasc: $("#dataNasc").val(),
                estado: select.options[select.selectedIndex].value
            };
            let url = '{{route('alunos.store')}}';
            $.ajaxSetup({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(url, aluno, function(data) {
                aluno = JSON.parse(data);
                var html = '<button type="button" class="btn btn-sm btn-primary textFont" onclick="editar(' + aluno.id +
                    ')">Editar</button>' + ' ';
                html += '<button type="button" class="btn btn-sm btn-danger textFont" onclick="excluir(' + aluno.id +
                    ')">Excluir</button>';
                var t = $('#tabelaAlunos').DataTable();

                t.row.add({
                    "id": aluno.id,
                    "alu_nome": aluno.alu_nome,
                    "alu_rge": aluno.alu_rge,
                    "alu_nasc": aluno.alu_nasc,
                    "action": html
                }).draw(false);
            }).done(function() {
                Swal.fire('Aluno(a) Cadastrado(a) com Sucesso!', '', 'success');
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

        function editar(id) {
            let url = '{{route('alunos.edit',':queryId')}}';
            url = url.replace(':queryId', id);
            $.getJSON(url, function(data) {
                document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Editar Aluno</h5>';
                aluno = data;
                console.log(aluno);
                $('#id').val(aluno[0].id);
                $('#nome').val(aluno[0].alu_nome);
                $('#registro').val(aluno[0].alu_rge);
                $('#dataNasc').val(aluno[0].alu_nasc);
                $("#estado option").filter(function() {
                    return this.text == aluno[0].est_nome;
                }).attr('selected', true);

                $('#dlgAlunos').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });
        }

        function salvarAluno() {
            var select = document.getElementById('estado');
            aluno = {
                id: $("#id").val(),
                nome: $("#nome").val(),
                registro: $("#registro").val(),
                dataNasc: $("#dataNasc").val(),
                estado: select.options[select.selectedIndex].value
            };
            let url = '{{route('alunos.update',':queryId')}}';
            url = url.replace(':queryId', aluno.id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'PUT',
                url: url,
                contentType: 'application/json',
                data: JSON.stringify(aluno),
            }).done(function() {
                var table = $('#tabelaAlunos').DataTable();
                table.row($(this).parents('tr')).draw(false);
                Swal.fire('Aluno(a) Atualizado(a) com Sucesso!', '', 'success')
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
                title: 'Confirma a exclusão desse(a) Aluno(a)?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não',
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '{{route('alunos.destroy',':queryId')}}';
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
                            var table = $('#tabelaAlunos').DataTable();
                            table.row($(this).parents('tr')).remove().draw(false);
                            Swal.fire('Aluno excluído com sucesso!','info');
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

        function carregarEstados() {
            let url = '{{route('estados.indexJsonA')}}';
            $.get(url, function(data) {
                for (i = 0; i < data.length; i++) {
                    opcao = '<option value = "' + data[i].id + '">' + data[i].est_nome + '</option>';
                    $('#estado').append(opcao);
                }
                document.getElementById('estado').value = -1;
            });
        }

        $(function() {
            carregarEstados();
            let url = '{{route('alunos.indexJSON')}}';
            $('#tabelaAlunos').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": url,
                columnDefs: [{
                    targets: 3,
                    render: function(data, type, row) {
                        return formataData(data);
                    },
                }],
                columns: [{
                        data: "id"
                    },
                    {
                        data: "alu_nome"
                    },
                    {
                        data: "alu_rge"
                    },
                    {
                        data: "alu_nasc"
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
                    $('#tabelaAlunos tfoot th').each(function() {
                        var title = $(this).text();
                        if (title === "Nome" || title === "Registro" || title ==
                            "Data Nascimento") {
                            $(this).html('<input type="text" placeholder="Filtrar ' + title +
                                '" />');
                        }
                    });
                    // Apply the search
                    this.api().columns([1, 2, 3]).every(function() {
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
