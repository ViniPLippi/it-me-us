@extends('layouts.principal', ["current" => "cidades"])
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
            <h4><b class="textFont">Lista de Cidades</b></h4>
        </div>
        <input type="hidden" id="tipouser" name="tipouser" class="form-control" value="{{ Auth::user()->type }}">
        <div class="card-body">
            <h5 class="card-title textFont" id="cardTitle"></h5>
            <table id="tabelaCidades" class="table table-striped textFont" style="width:100%">
                <thead class="tableHead">
                    <tr>
                        <th class="textFont">Código</th>
                        <th class="textFont">Nome</th>
                        <th class="textFont">Estado</th>
                        <th class="textFont">Código IBGE</th>
                        <th class="textFont"><span>Ações</span></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th class="textFont">Nome</th>
                        <th class="textFont">Estado</th>
                        <th class="textFont">IBGE</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer sectionTableFooter">
            <button type="button" class="btn btn-sm btn-primary btnGOV textFont" role="button" onClick="novoCidade()">Nova Cidade</a>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="dlgCidades">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formCidade" action="{{ route('cidades.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header textFont" id="cabecform">
                        <h5 class="modal-title textFont">Nova Cidade</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" class="form-control textFont">

                        <div class="form-group">
                            <label for="ibge" class="control-label textFont">Código IBGE</label>
                            <div class="input-group">
                                <input type="number" class="form-control textFont" id="ibge" name="ibge" placeholder="Código IBGE" required>
                                <button type="button" class="btn btn-sm btn-primary textFont" role="button" onClick="buscarIBGE()"> Obter</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nome" class="control-label textFont">Nome</label>
                            <div class="input-group">
                                <input type="text" class="form-control textFont" id="nome" name="nome" placeholder="Nome" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="estado" class="control-label textFont">Estado</label>
                            <div class="input-group">
                                <select class="form-control textFont" id="estado" name="estado" placeholder="Selecione um Estado" required>
                                    <option class="textFont" value="" selected>Selecione um Estado</option>
                               </select>
                            </div>
                        </div>
                        <div>
                            <hr class="solid">
                            <h5 class="modal-title textFont">Tempo de Aprender</h5>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline textFont">
                                <input class="form-check-input textFont" type="radio" name="tastatus" id="tastatusad" value="ad" required>
                                <label class="form-check-label textFont" for="tastatusad">Aderiu</label>
                            </div>
                            <div class="form-check form-check-inline textFont">
                                <input class="form-check-input textFont" type="radio" name="tastatus" id="tastatusna" value="nd" required>
                                <label class="form-check-label textFont" for="tastatusna">Não aderiu</label>
                            </div>
                        </div>
                        <div class="form-group textFont">
                            <label for="taadesao" class="control-label textFont" style="margin-right: 20px">Data de Adesão</label>
                            <input type="date" class="form-control textFont" style="margin-left: 10px" name="taadesao" id="taadesao" required>
                        </div>
                        <div class="form-group textFont">
                            <label for="taexclusao" class="control-label textFont" style="margin-right: 20px">Data de Exclusão</label>
                            <input type="date" class="form-control textFont" style="margin-left: 10px" name="taexclusao" id="taexclusao" required>
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
                                $('#dlgCidades').modal({
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
        function mudarLabelTaStatus() {
            if ($('#tastatus').is(':checked')) {
                $('#labeltastatus').val('Não aderiu');
            } else {
                $('#labeltastatus').val('Aderiu');
            }
        }
        function buscarIBGE() {
            var texto = $("#ibge").val();
            if (texto == '') {

                Swal.fire("Código do IBGE Necessário para Pesquisa!",'','error');
                document.getElementById('ibge').focus();
                return;
            }
            if (texto.length < 7 || texto.length > 7) {
                Swal.fire("Código do IBGE Inválido!",'','error');
                document.getElementById('ibge').focus();
                return;
            }
            pegaIBGE(texto, function(data) {
                if (data.length == 0) {
                    Swal.fire("Município não encontrado com esse código!",'','error');
                    document.getElementById('ibge').focus();
                    return;
                }
                $('#nome').val(data.nome);
                var text1 = data.microrregiao.mesorregiao.UF.nome;
                $("#estado option").filter(function() {
                    return this.text == text1;
                }).attr('selected', true);
            });
            document.getElementById('nome').focus();
        }

        function pegaIBGE(codigo, callback) {
            var servidor = 'https://servicodados.ibge.gov.br/api/v1/localidades/municipios/' + codigo;
            $.getJSON(servidor, function(data) {
                callback(data);
            });
        }

        function novoCidade() {
            document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Nova Cidade</h5>';
            document.getElementById("tastatusna").checked = true;
            document.getElementById("ibge").disabled = false;
            $('#id').val('');
            $('#nome').val('');
            $('#estado').val('');
            $('#ibge').val('');
            $('#tadesao').val('');
            $('#taexclusao').val('');
            $('#dlgCidades').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        }

        $("#dlgCidades").submit(function(event) {
            event.preventDefault();
            if ($("#id").val() != '') {
                salvarCidade();
            } else {
                criarCidade();
            }
            $("#dlgCidades").modal('hide');
        });

        function criarCidade() {
            var select = document.getElementById('estado');
            cidade = {
                nome: $("#nome").val(),
                estado: select.options[select.selectedIndex].value,
                ibge: $("#ibge").val(),
                tastatus: document.querySelector('input[name="tastatus"]:checked').value,
                taadesao: $("#taadesao").val(),
                taexclusao: $("#taexclusao").val()
            };

            let url = '{{route('cidades.store')}}';
            $.ajaxSetup({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(url, cidade, function(data) {
                cidade = JSON.parse(data);
                let urle = '{{route('estados.edit',':queryId')}}';
                urle = urle.replace(':queryId', cidade.cid_est_id);
                estado = $.getJSON(urle, function(data) {
                    return data.est_sigla;
                });
                var html = '<button type="button" class="btn btn-sm btn-primary textFont" onclick="editar(' + cidade.id +
                    ')">Editar</button>' + ' ';
                html += '<button type="button" class="btn btn-sm btn-danger textFont" onclick="excluir(' + cidade.id +
                    ')">Excluir</button>';
                var t = $('#tabelaCidades').DataTable();
                t.row.add({
                    "id": cidade.id,
                    "cid_nome": cidade.cid_nome,
                    "est_sigla": estado,
                    "cid_ibge": cidade.cid_ibge,
                    "action": html
                }).draw(false);

                Swal.fire("Cidade Cadastrada com Sucesso!",'','info');
            }).fail(function(jqXhr, json, errorThrown) { // this are default for ajax errors
                var errors = jqXhr.responseJSON;
                var errorsHtml = '';
                $.each(errors['errors'], function(index, value) {
                    errorsHtml += '<ul class="list-group textFont"><li class="list-group-item alert alert-danger textFont">' + value + '</li></ul>';
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
                    title: 'Erro(s) encontrado(s)!'
                });
            });
        }

        function editar(id) {
            let url = '{{route('cidades.edit',':queryId')}}';
            url = url.replace(':queryId', id);
            $.getJSON(url, function(data) {
                cidade = data;
                document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Editar Cidade</h5>';
                document.getElementById("ibge").disabled = true;
                $('#id').val(cidade[0].id);
                $('#nome').val(cidade[0].cid_nome);
                $('#ibge').val(cidade[0].cid_ibge);
                $("#estado option").filter(function() {
                    return this.text == cidade[0].est_nome;
                }).attr('selected', true);
                if(cidade[0].cid_ta_status==1){
                    document.getElementById('tastatusad').checked = true;
                } else {
                    document.getElementById('tastatusna').checked = true;
                }
                $('#tadesao').val(cidade[0].cid_ta_adesao);
                $('#taexclusao').val(cidade[0].cid_ta_exclusao);
                $('#dlgCidades').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });
        }

        function salvarCidade(){
            var select = document.getElementById('estado');
            cidade = {
                id: $("#id").val(),
                nome: $("#nome").val(),
                estado: select.options[select.selectedIndex].value,
                ibge: $("#ibge").val(),
                tastatus: document.querySelector('input[name="tastatus"]:checked').value,
                taadesao: $("#taadesao").val(),
                taexclusao: $("#taexclusao").val()
            };
            let url = '{{route('cidades.update',':queryId')}}';
            url = url.replace(':queryId', cidade.id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "PUT",
                url: url,
                data: cidade,
                success: function() {
                    var table = $('#tabelaCidades').DataTable();
                    table.row($(this).parents('tr')).draw(false);
                }
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
                    title: 'Erro(s) encontrado(s)!'
                });
            });
        }
        function excluir(id) {
            Swal.fire({
                title: 'Confirma a exclusão dessa Cidade?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não',
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '{{route('cidades.destroy',':queryId')}}';
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
                            var table = $('#tabelaCidades').DataTable();
                            table.row($(this).parents('tr')).remove().draw(false);
                            Swal.fire("Cidade excluída com sucesso!",'','info');
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
            let url = '{{route('cidades.indexJSON')}}';
            $('#tabelaCidades').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": url,
                columns: [{
                        "data": "id"
                    },
                    {
                        "data": "cid_nome"
                    },
                    {
                        "data": "est_sigla"
                    },
                    {
                        "data": "cid_ibge"
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
                },
                initComplete: function() {
                    $('#tabelaCidades tfoot th').each(function() {
                        var title = $(this).text();
                        if (title === "Nome" || title === "Estado" || title == "IBGE") {
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
