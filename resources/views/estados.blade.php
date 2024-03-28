@extends('layouts.principal', ['current' => 'estados'])
@section('content')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">


    <div class="card border tableProperties">
        <div class="card-header sectionTableHead">
            <h4><b class="textFont">Cadastro de Estados</b></h4>
        </div>
        <!--             <h5 class="card-title" id="cardTitle">Cadastro de Estados</h5> -->
        <div class="card-body ">
        <input type="hidden" id="tipouser" name="tipouser" class="form-control" value="{{ Auth::user()->type }}">
          </br>
            <h5 class="card-title textFont">Cadastro de Estados</h5>
            <table id="tabelaEstados" class="table table-striped textFont" style="width:100%">
                <thead class="tableHead">
                    <tr>
                        <th class="textFont">Código</th>
                        <th class="textFont">Nome</th>
                        <th class="textFont">Sigla</th>
                        <th class="textFont">Código IBGE</th>
                        <th class="textFont">Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="card-footer sectionTableFooter">
            <button class="btn btn-sm btn-primary textFont" id="novoEstado" role="button" onClick="novoEstado()">Novo Estado</a>
        </div>
     </div>
     <div class="modal" tabindex="-1" role="dialog" id="dlgEstados">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formChamado" action="{{ route('estados.store') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header" id="cabecform">
                        <h5 class="modal-title">Novo Estado</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" class="form-control">

                        <div class="form-group">
                            <label for="est_nome" class="control-label">Nome</label>
                            <div class="input-group">
                                <input type="text"
                                    class="form-control {{ $errors->has('est_nome') ? 'is-invalid' : '' || ' ' }}"
                                    id="nome" name="est_nome" placeholder="Nome" required>
                            </div>
                            @if ($errors->has('nome'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nome') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="est_sigla" class="control-label">Sigla</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="sigla" name="est_sigla"
                                    placeholder="Sigla" minlength="2" maxlength="2" style="text-transform: uppercase;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="est_ibge" class="control-label">Código IBGE</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="ibge" name="est_ibge"
                                    placeholder="Código IBGE">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="cancel" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger" role="alert">
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
                        $('#dlgEstados').modal({
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
        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function novoEstado() {
            document.getElementById("cabecform").innerHTML = '<h5 class="modal-title">Novo Estado</h5>';
            $('#id').val('');
            $('#nome').val('');
            $('#sigla').val('');
            $('#ibge').val('');
            $('#dlgEstados').modal('show');
        }

        $("#dlgEstados").submit(function(event) {
            event.preventDefault();
            if ($("#id").val() != '') {
                salvarEstado();
            } else {
                criarEstado();
            }
            $("#dlgEstados").modal('hide');
        });

        function criarEstado() {
            estado = {
                est_nome: $("#nome").val(),
                est_sigla: $("#sigla").val(),
                est_ibge: $("#ibge").val()
            }

            let url = '{{route('estados.store')}}';
            //            url = url.replace(':queryId', calculatedId);
            $.ajaxSetup({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(url, estado, function(data) {
                estado = JSON.parse(data);
                var html = '<button class="btn btn-sm btn-primary" onclick="editar(' + estado.id +
                    ')">Editar</button>' + ' ';
                html += '<button class="btn btn-sm btn-danger" onclick="excluir(' + estado.id +
                    ')">Excluir</button>';
                var t = $('#tabelaEstados').DataTable();
                t.row.add({
                    "id": estado.id,
                    "est_nome": estado.est_nome,
                    "est_sigla": estado.est_sigla,
                    "est_ibge": estado.est_ibge,
                    "action": html
                }).draw(false);
                Swal.fire('Estado Cadastrado com Sucesso!', '', 'info');
            }).fail(function(jqXhr, json, errorThrown) { // this are default for ajax errors
                var errors = jqXhr.responseJSON;
                var errorsHtml = '';
                $.each(errors['errors'], function(index, value) {
                    errorsHtml += '<ul class="list-group"><li class="list-group-item alert alert-danger">' +
                        value + '</li></ul>';
                });
                const Toast = Swal.mixin({
                    title: "Error " + jqXhr.status + ': ' +
                    errorThrown, // this will output "Error 422: Unprocessable Entity"
                    html: errorsHtml,
                    width: 'auto'
                }, function(isConfirm) {
                    if (isConfirm) {
                        $('#openModal').click(); //this is when the form is in a modal
                    }
                });
                Toast.fire({
                    icon: 'error',
                    title: 'Erro(s) encontrado(s)!'
                });
            });
        };

        function editar(id) {
            let url = '{{route('estados.edit',':queryId')}}';
            url = url.replace(':queryId', id);

            $.getJSON(url, function(data) {
                document.getElementById("cabecform").innerHTML = '<h5 class="modal-title ">Editar Estado</h5>';
                $('#id').val(data.id);
                $('#nome').val(data.est_nome);
                $('#sigla').val(data.est_sigla);
                $('#ibge').val(data.est_ibge);
                $('#dlgEstados').modal('show');
            });
        }

        function excluir(id) {
            Swal.fire({
                title: 'Confirma a exclusão desse Estado?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não',
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '{{route('estados.destroy',':queryId')}}';
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
                            var table = $('#tabelaEstados').DataTable();
                            table.row($(this).parents('tr')).remove().draw(false);
                            Swal.fire('Estado excluído com Sucesso!', '', 'info');
                        }                    
                    }).fail(function(jqXhr, json, errorThrown) {
                        var errors = jqXhr.responseJSON;
                        var errorsHtml = '';
                        $.each(errors['errors'], function(index, value) {
                            errorsHtml += '<ul class="list-group"><li class="list-group-item alert alert-danger">' +
                                value + '</li></ul>';
                        });
                        const Toast = Swal.mixin({
                            title: "Error 409: " + errorThrown,
                            html: errorsHtml,
                            width: 'auto'
                        }, function(isConfirm) {
                            if (isConfirm) {
                                $('#openModal').click();
                            }
                        });
                        Toast.fire({
                            icon: 'error',
                            title: 'Erro encontrado!'
                        });
                    });
                } else if (result.isDenied) {
                    Swal.fire('Exclusão não foi realizada!', '', 'info');
                }
            })
        }

        function salvarEstado() {
            estado = {
                id: $("#id").val(),
                nome: $("#nome").val(),
                sigla: $("#sigla").val(),
                ibge: $("#ibge").val()
            }
            let url = '{{route('estados.update',':queryId')}}';
            url = url.replace(':queryId', estado.id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "PUT",
                url: url,
                data: estado,
                success: function() {
                    var table = $('#tabelaEstados').DataTable();
                    table.row($(this).parents('tr')).draw(false);
                }
            }).fail(function(jqXhr, json, errorThrown) {
                var errors = jqXhr.responseJSON;
                var errorsHtml = '';
                $.each(errors['errors'], function(index, value) {
                    errorsHtml += '<ul class="list-group"><li class="list-group-item alert alert-danger">' +
                        value + '</li></ul>';
                });
                const Toast = Swal.mixin({
                    title: "Error " + jqXhr.status + ': ' + errorThrown,
                    html: errorsHtml,
                    width: 'auto'
                }, function(isConfirm) {
                    if (isConfirm) {
                        $('#openModal').click();
                    }
                });
                Toast.fire({
                    icon: 'error',
                    title: 'Erro(s) encontrado(s)!'
                });
            });
        };

        $(function() {
            let url = '{{route('estados.indexdtJson')}}';
            $('#tabelaEstados').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": url,
                columns: [{
                        "data": "id"
                    },
                    {
                        "data": "est_nome"
                    },
                    {
                        "data": "est_sigla"
                    },
                    {
                        "data": "est_ibge"
                    },
                    {
                        "data": "action",
                        name: 'action',
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
                }
            });
        });
    </script>
@endsection
