@extends('layouts.principal', ['current' => 'sala'])
@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="col d-flex justify-content-center">
        <div class="card border tableProperties" style="width:auto;">
            <div class="card-header sectionTableHead">
                <h4><b class="textFont">Sala de Situação</b></h4>
            </div>
            <div class="card-body">
                <h5 class="card-title textFont" id="cardTitle"></h5>
                <div class="form-select w-60 mt-0 mb-2 pt-0">
                    <input type="hidden" id="tipouser" name="tipouser" class="form-control textFont"
                        value="{{ Auth::user()->type }}">
                    <div class="input-group">
                        <div class="form-group row">
                            <div class="form-group col-sm-8 mt-0 mb-1 pt-0" style="width:80rem">
                                <label for="testes" class="control-label textFont">Selecione um Teste</label>
                                <div class="input-group" style="width:auto">
                                    <div class="input-group-prepend mb-2 textFont">
                                        <div class="input-group-text textFont"><strong>Teste</strong></div>
                                    </div>
                                    <select class="form-control textFont" style="width:auto" id="testes" name="testes"
                                        placeholder="Selecione Teste..." width="50" required>
                                    </select>
                                </div>
                                <label for="estado" class="control-label">Selecione a UF</label>
                                <div class="input-group">
                                    <div class="input-group-prepend mb-2 textFont">
                                        <div class="input-group-text textFont"><strong>UF</strong></div>
                                    </div>
                                    <select class="form-control textFont" id="estado" name="estado"
                                        placeholder="Selecione um Estado" width="50" onchange="carregarCidadesUF()">
                                    </select>
                                </div>
                                <label for="cidade" class="control-label">Selecione a Cidade</label>
                                <div class="input-group">
                                    <div class="input-group-prepend mb-2 textFont">
                                        <div class="input-group-text textFont"><strong>Cidade</strong></div>
                                    </div>
                                    <select class="form-control textFont" id="cidade" name="cidade"
                                        placeholder="Selecione uma Cidade" width="50"
                                        onchange="carregarEscolasCidade()">
                                    </select>
                                </div>
                                <label for="escola" class="control-label">Selecione a Escola</label>
                                <div class="input-group">
                                    <div class="input-group-prepend mb-2 textFont">
                                        <div class="input-group-text textFont"><strong>Escola</strong></div>
                                    </div>
                                    <select class="form-control textFont" id="escola" name="escola"
                                        placeholder="Selecione uma Escola" width="50" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fa fa-star-o fa-sm"
                                        aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-xs">A Iniciar</span>
                                    <span class="info-box-number" id="infoainiciar"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fa fa-star-half-o fa-sm"
                                        aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-xs">Iniciado</span>
                                    <span class="info-box-number" id="infoiniciado"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-gradient-warning"><i class="fa fa-star fa-sm"
                                        aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-xs">Finalizado</span>
                                    <span class="info-box-number" id="infofinalizado"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-gradient-danger"><i class="fa fa-flag-o fa-sm"
                                        aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-xs">Atenção</span>
                                    <span class="info-box-number" id="infoatencao"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-gradient-secondary"><i class="fa fa-print fa-sm"
                                        aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-xs">Relatórios</span>
                                    <span class="info-box-number" id="inforelatorios"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-gradient-dark"><i class="fa fa-bar-chart fa-sm"
                                        aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-xs">Gráficos</span>
                                    <span class="info-box-number" id="infograficos"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
    @if (Session::has('errors'))
        <script>
            $(document).ready(function() {});
        </script>
    @endif
    </div>
@endsection
<!-------------------- Código Javascript --------------------------->

@section('javascript')
    <script type="text/javascript">
        var ret;
        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        $(document).ready(function() {

            /* Mask for Zip code */
            Inputmask("9{3}.9{3}.9{3}-9{2}", {
                placeholder: "-",
                greedy: false
            }).mask('#cpf');

            /* Mask for Zip code */
            Inputmask("9{5}-9{3}", {
                placeholder: "-",
                greedy: false
            }).mask('#cep');

            /* Mask for Phone */
            Inputmask("(9{2}) 9{9}", {
                placeholder: "-",
                greedy: false,
            }).mask('#telefone');

            /* Mask for Inep code */
            Inputmask("9{8}", {
                placeholder: "-",
                greedy: false
            }).mask('#inep');

        });

        function formataData(data) {
            var dataiso = new Date(data);
            var dataFormatada = adicionaZero(dataiso.getDate() + 1) + "/" + adicionaZero(dataiso.getMonth() + 1) + "/" +
                dataiso
                .getFullYear();
            return dataFormatada;
        }

        function adicionaZero(numero) {
            if (numero <= 9)
                return "0" + numero;
            else
                return numero;
        }

        function convNivel(data) {
            switch (data) {
                case 0:
                    return 'Nacional';
                    break;
                case 1:
                    return 'Estadual';
                    break;
                case 2:
                    return 'Municipal';
                    break;
                case 3:
                    return 'Escola';
                    break;
                default:
                    return 'Indefinido'
            }
        }

        function convStatus(data) {
            switch (data) {
                case 0:
                    return 'Não Iniciado';
                    break;
                case 1:
                    return 'Iniciado';
                    break;
                case 2:
                    return 'Fechado';
                    break;
                case 3:
                    return 'Desativado';
                    break;
                default:
                    return 'Indefinido'
            }
        }

        function carregarTestesFiltro() {
            let url = '{{ route('testes.indexJSONF') }}';
            $.get(url, function(data) {
                opcao =
                    '<option selected value = "-1" data-valor="TT" class="textFont"> Selecione um Teste...</option>';
                $('#testes').append(opcao);
                for (i = 0; i < data.length; i++) {
                    opcao = '<option class="textFont" value = "' + data[i].id + '" data-valor="' + data[i].id +
                        '">' +
                        data[i].tst_descricao + '</option>';
                    $('#testes').append(opcao);
                }
                document.getElementById('testes').value = -1;
            });
        }

        function carregarTestes(id) {
            document.querySelector('#testes').innerHTML = '';
            carregarTestesFiltro();
            $('#cpfaplic').val('');
            $('#nome_professor_aplic').val('');
            let url = '{{ route('turmas.edit', ':queryId') }}';
            url = url.replace(':queryId', id);
            $.getJSON(url, function(data) {
                turma = data;
                $('#turma_id').val(turma[0].id);
                document.getElementById("cabecformteste").innerHTML =
                    '<h5 class="modal-title textFont"><b>Lista de Testes da Turma</b>: ' +
                    turma[0].id + " - " +
                    turma[0].tur_nome + " - " +
                    turma[0].tur_ano + "/" +
                    turma[0].tur_serie + '</h5>';

                $('#dlgTestes').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            var tableId = "#tabelaTestes";

            if ($('#tabelaTestes').DataTable() != null) {
                $('#tabelaTestes').DataTable().clear();
                $('#tabelaTestes').DataTable().destroy();
            }

            $(tableId + " tbody").empty();

            let url2 = '{{ route('turmahasaplicadorhasteste.indexJSON', ':queryId') }}';
            url2 = url2.replace(':queryId', id);

            $('#tabelaTestes').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": url2,
                    "contentType": "application/json",
                    "type": "GET",
                    "data": function(data) {
                        return data;
                    }
                },
                columnDefs: [{
                        "width": "8%",
                        "targets": [0, 3, 4]
                    },
                    {
                        "width": "26%",
                        "targets": 1
                    },
                    {
                        "width": "13%",
                        "targets": [2, 5]
                    },
                    {
                        "width": "24%",
                        "targets": [6]
                    },
                    {
                        "targets": [3, 4],
                        "render": function(data, type, row) {
                            return formataData(data);
                        }
                    },
                    {
                        "targets": 2,
                        "render": function(data, type, row) {
                            return convNivel(data);
                        }
                    },
                    {
                        "targets": 5,
                        "render": function(data, type, row) {
                            return convStatus(data);
                        }
                    }
                ],
                columns: [{
                        "data": "id"
                    },
                    {
                        "data": "tst_descricao"
                    },
                    {
                        "data": "tst_principal"
                    },
                    {
                        "data": "tst_ini"
                    },
                    {
                        "data": "tst_fim"
                    },
                    {
                        "data": "tst_status"
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
                    "thousands": ".",
                    "processing": "Processando...\nAguarde!"
                },
                initComplete: function() {
                    $('#tabelaTestes tfoot th').each(function() {
                        var title = $(this).text();
                        if (title === "Descrição") {
                            $(this).html(
                                '<input class="textFont" type="text" placeholder="Filtrar Descrição" style="width:300px"/>'
                            );
                        }
                        if (title === "Nível") {
                            $(this).html(
                                '<input class="textFont" type="text" placeholder="Filtrar Nível" style="width:100px"/>'
                            );
                        }
                        if (title == "Status") {
                            $(this).html(
                                '<input class="textFont" type="text" placeholder="Filtrar Status" style="width:100px" />'
                            );
                        }

                    });
                    // Apply the search
                    this.api().columns([1, 2, 5]).every(function() {
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
        }

        function carregarAlunos(id) {
            $('#rge').val('');
            $('#nome_aluno').val('');
            $('#nasc_aluno').val('');
            $('#estado_aluno').val('');

            let url = '{{ route('turmas.edit', ':queryId') }}';
            url = url.replace(':queryId', id);

            $.getJSON(url, function(data) {
                turma = data;
                $('#turma_id').val(turma[0].id);
                document.getElementById("cabecformaluno").innerHTML =
                    '<h5 class="modal-title textFont"><b>Lista de Alunos Turma</b>: ' +
                    turma[0].id + " - " +
                    turma[0].tur_nome + " - " +
                    turma[0].tur_ano + "/" +
                    turma[0].tur_serie + '</h5>';

                $('#dlgAlunos').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });
            var tableId = "#tabelaAlunos";

            if ($('#tabelaAlunos').DataTable() != null) {
                $('#tabelaAlunos').DataTable().clear();
                $('#tabelaAlunos').DataTable().destroy();
            }

            $(tableId + " tbody").empty();

            let url2 = '{{ route('turmahasalunos.indexJSON', ':queryId') }}';
            url2 = url2.replace(':queryId', id);

            $('#tabelaAlunos').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": url2,
                    "contentType": "application/json",
                    "type": "GET",
                    "data": function(data) {
                        return data;
                    }
                },
                "columnDefs": [{
                        "width": "5%",
                        "targets": 0
                    },
                    {
                        "width": "40%",
                        "targets": 1
                    },
                    {
                        "width": "10%",
                        "targets": 2
                    },
                    {
                        "width": "5%",
                        "targets": 3
                    }
                ],
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
                        if (title === "Nome" || title === "Registro") {
                            $(this).html('<input class="textFont" type="text" placeholder="Filtrar ' +
                                title +
                                '" />');
                        }
                    });
                    this.api().columns([1, 2]).every(function() {
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
        }

        function novoTurma() {
            document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Nova Turma</h5>';
            $('#id').val('');
            $('#nome').val('');
            $('#ano').val('');
            $('#serie').val('');
            $('#cpf').val('');
            $('#nome_professor').val('');

            $('#dlgTurmas').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        }

        $("#dlgTurmas").submit(function(event) {
            event.preventDefault();
            if ($("#id").val() != '') {
                salvarTurma();
            } else {
                criarTurma();
            }
            $("#dlgTurmas").modal('hide');
        });

        function criarTurma() {
            turma = {
                nome: $("#nome").val(),
                ano: $("#ano").val(),
                serie: $("#serie").val(),
                professor_id: $("#professor_id").val(),
                esc_id: $("#esc_id").val()
            };

            let url = '{{ route('turmas.store') }}';

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                data: turma,
                success: function(data) {
                    turma = JSON.parse(data);
                    var html =
                        '<button type="button" class="btn btn-sm btn-primary mr-2 textFontBold" id="editarTeste" onclick="editar(' +
                        turma.id + ')">Editar</button>';
                    html +=
                        '<button type="button" class="btn btn-sm btn-danger mr-2 textFontBold" onclick="excluir(' +
                        turma
                        .id + ')">Excluir</button>';
                    html +=
                        '<button type="button" class="btn btn-sm btn-success mr-2 btn-turma textFontBold"  onclick="carregarAlunos(' +
                        turma
                        .id + ')">Alunos</button>';
                    html +=
                        '<button type="button" class="btn btn-sm btn-warning mr-2 textFontBold" onclick="carregarTestes(' +
                        turma.id +
                        ')">Testes</button>';
                    var t = $('#tabelaTurmas').DataTable();
                    t.row.add({
                        "id": turma.id,
                        "tur_nome": turma.tur_nome,
                        "tur_ano": turma.tur_ano,
                        "tur_serie": turma.tur_serie,
                        "action": html
                    }).draw(false);
                }
            }).done(function() {
                Swal.fire('Turma Cadastrada com Sucesso!', '', 'info');
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
                    title: 'Erros encontrados!'
                });
            });
        }

        function editar(id) {
            let url = '{{ route('turmas.edit', ':queryId') }}';
            url = url.replace(':queryId', id);
            $.getJSON(url, function(data) {
                turma = data;
                document.getElementById("cabecform").innerHTML =
                    '<h5 class="modal-title textFont">Editar Turma</h5>';
                $('#id').val(turma[0].id);
                $('#nome').val(turma[0].tur_nome);
                $('#ano').val(turma[0].tur_ano);
                $('#serie').val(turma[0].tur_serie);
                $('#esc_id').val(turma[0].tur_esc_id);
                $('#professor_id').val(turma[0].tur_users_id_professor);
                $('#cpf').val(turma[0].cpf);
                $('#nome_professor').val(turma[0].name);

                $('#dlgTurmas').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });
        }

        function salvarTurma() {
            turma = {
                id: $("#id").val(),
                nome: $("#nome").val(),
                ano: $("#ano").val(),
                serie: $("#serie").val(),
                professor_id: $("#professor_id").val(),
                esc_id: $("#esc_id").val()
            };
            let url = '{{ route('turmas.update', ':queryId') }}';
            url = url.replace(':queryId', turma.id);
            $.ajax({
                type: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                data: turma,
            }).done(function() {
                var table = $('#tabelaTurmas').DataTable();
                table.row($(this).parents('tr')).draw(false);
                Swal.fire('Turma Atualizada com Sucesso!', '', 'success');
            }).fail(function(jqXhr, json, errorThrown) {
                var errors = jqXhr.responseJSON;
                var errorsHtml = '';
                $.each(errors['errors'], function(index, value) {
                    errorsHtml += '<ul class="list-group"><li class="list-group-item alert alert-danger">' +
                        value + '</li></ul>';
                });
                //I use SweetAlert2 for this
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
                    title: 'Erros encontrados!'
                });
            });
        }

        function incluirAlunoTurma() {
            turmahasalunos = {
                aluno_id: $("#aluno_id").val(),
                turma_id: $("#turma_id").val(),
            };
            let url = '{{ route('turmahasalunos.store') }}';
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                data: turmahasalunos,
                success: function(data) {
                    turmahasalunos = JSON.parse(data);
                    var html =
                        '<button type="button" class="btn btn-sm btn-danger textFont" onclick="excluirAluno(' +
                        turmahasalunos.id + ')">Excluir</button>';
                    var t = $('#tabelaAlunos').DataTable();
                    t.row.add({
                        "id": turmahasalunos[0].id,
                        "alu_nome": turmahasalunos[0].alu_nome,
                        "alu_rge": turmahasalunos[0].alu_rge,
                        "action": html
                    }).draw(false);
                }
            }).done(function() {
                Swal.fire('Aluno(a) Incluído(a) na Turma com Sucesso!', '', 'info');
                $('#rge').val('');
                $('#nome_aluno').val('');
                $('#nasc_aluno').val('');
                $('#estado_aluno').val('');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                Swal.fire('Falha ao Incluir esse Aluno na Turma!\n' + jqXHR.responseText, '', 'error');
            });
        }

        function excluir(id) {
            Swal.fire({
                title: 'Confirma a exclusão dessa Turma?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não',
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '{{ route('turmas.destroy', ':queryId') }}';
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
                            var table = $('#tabelaTurmas').DataTable();
                            table.row($(this).parents('tr')).remove().draw(false);
                            Swal.fire('Turma excluída com sucesso!', '', 'success');
                        },
                        error: function(error) {
                            Swal.fire('Ocorreu um erro ao excluir:\n' + error, '', 'error');
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire('Exclusão não foi realizada!', '', 'info')
                }
            })

        }

        function excluirAluno(id) {
            Swal.fire({
                title: 'Confirma a exclusão desse(a) Aluno(a)?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não',
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '{{ route('turmahasalunos.destroy', ':queryId') }}';
                    url = url.replace(':queryId', id);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        type: 'DELETE',
                        data: {
                            somefield: "Some field value",
                            _token: '{{ csrf_token() }}'
                        },
                        context: this,
                        success: function() {
                            var table = $('#tabelaAlunos').DataTable();
                            table.row($(this).parents('tr')).remove().draw(false);
                            Swal.fire('Aluno(a) excluído(a) com sucesso!', '', 'success');
                        },
                        error: function(error) {
                            Swal.fire('Ocorreu um erro ao excluir:\n' + error, '', 'error');
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire('Exclusão não foi realizada!', '', 'info')
                }
            })

        }

        function carregarTurmas() {

            var texto = $("#inep").val().replace(/[^0-9]/g, '');
            if (texto == '') {
                Swal.fire('Necessário Informar Código INEP da Escola!', '', 'info');
                document.getElementById('inep').focus();
                return;
            }

            if (texto.length != 8) {
                Swal.fire('Código INEP Inválido!', '', 'info');
                document.getElementById('inep').focus();
                return;
            }

            let url = '{{ route('escolas.indexINEP', ':queryId') }}';
            url = url.replace(':queryId', $('#inep').val());

            $.ajax({
                dataType: "json",
                url: url,
                success: function(data) {
                    if (data.length == 0) {
                        Swal.fire('Escola não cadastrada!', '', 'info');
                        document.getElementById('inep').focus();
                        return;
                    }

                    $('#esc_id').val(data[0].id);
                    $('#razao').val(data[0].esc_razao);
                    $('#cidade').val(data[0].cid_nome);
                    $('#uf').val(data[0].est_sigla);

                    document.getElementById("botaoNovaTurma").disabled = false;

                    var tableId = "#tabelaTurmas";

                    if ($('#tabelaTurmas').DataTable() != null) {
                        $('#tabelaTurmas').DataTable().clear();
                        $('#tabelaTurmas').DataTable().destroy();
                    }

                    $(tableId + " tbody").empty();

                    let url = '{{ route('turmas.indexJSON', ':queryId') }}';
                    url = url.replace(':queryId', data[0].id);

                    $('#tabelaTurmas').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": url,
                        "columnDefs": [{
                                "width": "5%",
                                "targets": 0
                            },
                            {
                                "width": "28%",
                                "targets": 1
                            },
                            {
                                "width": "4%",
                                "targets": 2
                            },
                            {
                                "width": "4%",
                                "targets": 3
                            },
                            {
                                "width": "44%",
                                "targets": 4
                            },
                        ],
                        columns: [{
                                data: "id"
                            },
                            {
                                data: "tur_nome"
                            },
                            {
                                data: "tur_ano"
                            },
                            {
                                data: "tur_serie"
                            },
                            {
                                data: "action",
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
                            "thousands": ".",
                            "processing": "Processando...\nAguarde!"
                        },
                        initComplete: function() {
                            $('#tabelaTurmas tfoot th').each(function() {
                                var title = $(this).text();
                                if (title === "Nome") {
                                    $(this).html(
                                        '<input class="textFont" type="text" placeholder="Filtrar Nome" />'
                                    );
                                }
                                if (title === "Ano Calendário") {
                                    $(this).html(
                                        '<input class="textFont" type="text" placeholder="Filtrar Ano Calendário" />'
                                    );
                                }
                                if (title == "Ano") {
                                    $(this).html(
                                        '<input class="textFont" type="text" placeholder="Filtrar Ano" />'
                                    );
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
                }
            }).fail(function() {
                Swal.fire('Escola não cadastrada!', '', 'info');
                document.getElementById('inep').focus();
            });
        }

        function carregarProfessor(id) {

            if (id == 1) {
                var texto = $("#cpf").val().replace(/[^0-9]/g, '');
                if (texto == '') {
                    Swal.fire('Necessário Informar Código CPF do Professor!', '', 'info');
                    document.getElementById('cpf').focus();
                    return;
                }

                if (texto.length != 11) {
                    Swal.fire('CPF Inválido!', '', 'info');
                    document.getElementById('cpf').focus();
                    return;
                }
            } else {
                var texto = $("#cpfaplic").val().replace(/[^0-9]/g, '');
                if (texto == '') {
                    Swal.fire('Necessário Informar Código CPF do Professor Aplicador!', '', 'info');
                    document.getElementById('cpfaplic').focus();
                    return;
                }

                if (texto.length != 11) {
                    Swal.fire('CPF Inválido!', '', 'info');
                    document.getElementById('cpfaplic').focus();
                    return;
                }
            }
            let url = '{{ route('turmas.indexUser', ':queryId') }}';
            url = url.replace(':queryId', texto);
            $.ajax({
                dataType: "json",
                url: url,
                success: function(data) {
                    if (data.length == 0) {
                        Swal.fire('CPF não cadastrado!', '', 'info');
                        document.getElementById('cpf').focus();
                        return;
                    }

                    if (id == 1) {
                        $('#professor_id').val(data[0].id);
                        $('#nome_professor').val(data[0].name);
                    } else {
                        $('#professoraplic_id').val(data[0].id);
                        $('#nome_professor_aplic').val(data[0].name);
                    }
                }
            }).fail(function() {
                Swal.fire('CPF não cadastrado!', '', 'info');
                document.getElementById('cpf').focus();
            });
        }

        function formataData(data) {
            var dataiso = new Date(data);
            var dataFormatada = adicionaZero(dataiso.getDate()) + "/" + adicionaZero(dataiso.getMonth() + 1) + "/" + dataiso
                .getFullYear();
            return dataFormatada;
        }

        function adicionaZero(numero) {
            if (numero <= 9)
                return "0" + numero;
            else
                return numero;
        }

        function incluirTeste() {
            tat = {
                professoraplic_id: $("#professoraplic_id").val(),
                turma_id: $("#turma_id").val(),
                teste_id: testes.options[testes.selectedIndex].value,
                status: 0
            };
            let url = '{{ route('turmahasaplicadorhasteste.store') }}';
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                data: tat,
                success: function(data) {
                    tat = JSON.parse(data);
                    html = '<button type="button" class="btn btn-sm btn-danger mr-2" onclick="excluirTeste(' +
                        tat[0].id + ')">Excluir</button>';
                    html +=
                        '<button type="button" class="btn btn-sm btn-success mr-2" onclick="carregarAplicador(' +
                        tat[0].tat_users_id_aplicador + ')">Aplicador</button>';
                    var t = $('#tabelaTestes').DataTable();
                    t.row.add({
                        "id": tat[0].id,
                        "tst_descricao": tat[0].tst_descricao,
                        "tst_principal": convNivel(tat[0].tst_principal),
                        "tst_ini": formataData(tat[0].tst_ini),
                        "tst_fim": formataData(tat[0].tst_fim),
                        "tst_status": convStatus(tat[0].tst_status),
                        "action": html
                    }).draw(false);
                }
            }).done(function() {
                Swal.fire('Teste Associado à Turma com Sucesso!', '', 'info');
                document.querySelector('#testes').innerHTML = '';
                $('#cpfaplic').val('');
                $('#nome_professor_aplic').val('');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                Swal.fire('Falha ao Associar este Teste à Turma!\n' + jqXHR.responseText, '', 'error');
            });
        }

        function carregarAluno() {

            var texto = $("#rge").val().replace(/[^0-9]/g, '');
            if (texto == '') {
                Swal.fire('Necessário Informar Código Registro do Aluno!', '', 'info');
                document.getElementById('rge').focus();
                return;
            }

            if (texto.length > 15) {
                Swal.fire('Registro de Aluno Inválido!', '', 'info');
                document.getElementById('rge').focus();
                return;
            }
            let url = '{{ route('turmas.indexAluno', ':queryId') }}';
            url = url.replace(':queryId', texto);
            $.ajax({
                dataType: "json",
                url: url,
                success: function(data) {
                    if (data.length == 0) {
                        Swal.fire('Registro do(a) Aluno(a) não cadastrado!', '', 'info');
                        document.getElementById('rge').focus();
                        return;
                    }

                    $('#aluno_id').val(data[0].id);
                    $('#nome_aluno').val(data[0].alu_nome);
                    $('#nasc_aluno').val(formataData(data[0].alu_nasc));
                    $('#estado_aluno').val(data[0].est_sigla);


                }
            }).fail(function() {
                Swal.fire('Registro não cadastrado!', '', 'info');
                document.getElementById('rge').focus();
            });
        }

        function carregarEstados() {
            let url = '{{ route('estados.indexJsonA') }}';
            $.get(url, function(data) {
                opcao = '<option class="textFont" value = "-1" data-valor="TT"> Todas as UFs</option>';
                $('#estado').append(opcao);
                for (i = 0; i < data.length; i++) {
                    opcao = '<option class="textFont" value = "' + data[i].id + '" data-valor="' + data[i].sigla +
                        '">' +
                        data[i]
                        .est_nome + '</option>';
                    $('#estado').append(opcao);
                }
                document.getElementById('estado').value = -1;
            });
        }

        function carregarCidadesUF() {
            var select = document.getElementById("cidade");
            while (select.options.length > 0) {
                select.options[0].remove();
            }
            opcao = '<option class="textFont" value = "-1" data-valor="TT"> Todas as Cidades</option>';
            $('#cidade').append(opcao);
            let uf = $('#estado').val();
            if (uf == -1) {
                return;
            }
            let url = '{{ route('cidades.estadosuf', ':queryId') }}';
            url = url.replace(':queryId', uf);
            $.get(url, function(data) {
                for (i = 0; i < data.length; i++) {
                    opcao = '<option class="textFont" value = "' + data[i].id + '" data-valor="' + data[i]
                        .cid_ibge + '">' +
                        data[i].cid_nome + '</option>';
                    $('#cidade').append(opcao);
                }
                document.getElementById('cidade').value = -1;
            });
        }

        function carregarEscolasCidade() {
            var select = document.getElementById("escola");
            while (select.options.length > 0) {
                select.options[0].remove();
            }
            opcao = '<option class="textFont" value = "-1" data-valor="TT"> Todas as Escolas</option>';
            $('#escola').append(opcao);
            let cid = $('#cidade').val();
            if (cid == -1) {
                return;
            }
            let url = '{{ route('escolas.indexcidade', ':queryId') }}';
            url = url.replace(':queryId', cid);
            $.get(url, function(data) {
                console.log(data);
                for (i = 0; i < data.length; i++) {
                    opcao = '<option class="textFont" value = "' + data[i].id + '" data-valor="' + data[i]
                        .esc_inep + '">' +
                        data[i].esc_razao + '</option>';
                    $('#escola').append(opcao);
                }
                document.getElementById('escola').value = -1;
            });
        }

        function atualizarInfos() {
            document.getElementById('infoainiciar').innerHTML = 1500;
            document.getElementById('infoiniciado').innerHTML = 300;
            document.getElementById('infofinalizado').innerHTML = 50;
            document.getElementById('infoatencao').innerHTML = 5;
        }

        $(function() {
            carregarTestesFiltro();
            carregarEstados();
            atualizarInfos();
        });
    </script>
@endsection
