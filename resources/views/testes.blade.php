@extends('layouts.principal', ['current' => 'testes'])
@section('content')
    <div class="card border tableProperties">
        <div class="card-header sectionTableHead">
            <h4><b class="textFont">Lista de Testes</b></h4>
        </div>
        <input type="hidden" id="tipouser" name="tipouser" class="form-control" value="{{ Auth::user()->type }}">
        <div class="card-body">
            <h5 class="card-title textFont" id="cardTitle"></h5>
            <table id="tabelaTestes" class="table table-striped">
                <thead class="tableHead">
                    <tr>
                        <th class="textFont">Código</th>
                        <th class="textFont">Descrição</th>
                        <th class="textFont">Nível</th>
                        <th class="textFont">Início</th>
                        <th class="textFont">Final</th>
                        <th class="textFont">Status</th>
                        <th class="textFont">Ações</th>
                    </tr>
                </thead>
                <tbody class="tBodyTestes">
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th class="textFont">Descrição</th>
                        <th class="textFont">Nível</th>
                        <th></th>
                        <th></th>
                        <th class="textFont">Status</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer sectionTableFooter">
            <button class="btn btn-sm btn-primary btnGOV textFont" role="button" id="botaoNovoTeste" onClick="novoTeste()">Novo
                Teste</a>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="dlgTestes">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formteste" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header-sm mt-1 mb-0 ml-3 textFont" style="width: 90%;border-bottom: 1px solid #000000;"
                        id="cabecform">
                        <h5 class="modal-titles textFont">Novo Teste</h5>
                    </div>
                    <div class="modal-body mt-0 textFont">
                        <input type="hidden" id="id" name="id" class="form-control textFont">
                        <input type="hidden" id="sec_id" name="sec_id" class="form-control textFont">
                        <input type="hidden" id="esc_id" name="esc_id" class="form-control textFont">

                        <div class="form-group mt-0">
                            <label for="descricao" class="control-label mt-0 textFont">Descrição</label>
                            <div class="input-group input-group-sm textFont">
                                <input type="text" class="form-control textFont" id="descricao" name="descricao"
                                    placeholder="Descrição" maxlength="50">
                            </div>
                        </div>

                        <div class="form-group row input-group-sm">
                            <div class="input-group-prepend input-group-sm mb-1">
                                <div id="labelnivel" name="labelnivel" class="input-group-text input-group-sm ml-2 mb-1">
                                    <strong class="textFont">Nível</strong>
                                </div>
                                <select class="form-select form-select-sm mr-2 textFont" aria-label=".form-select-sm example"
                                    name="principal" id="principal" onChange="mudarPrincipal()">
                                    <option class="textFont" selected>Selecione...</option>
                                    <option class="textFont" value="0">Nacional</option>
                                    <option class="textFont" value="1">Estadual</option>
                                    <option class="textFont" value="2">Municipal</option>
                                    <option class="textFont" value="3">Escola</option>
                                </select>
                            </div>
                            <div class="input-group-prepend input-group-sm mb-1">
                                <div id="labelstatus" name="labelstatus" class="input-group-text input-group-sm mb-1">
                                    <strong class="textFont">Status</strong>
                                </div>
                                <select class="form-select form-select-sm textFont" aria-label=".form-select-sm example"
                                    name="status" id="status">
                                    <option class="textFont" selected>Selecione...</option>
                                    <option class="textFont" value="0">Não Iniciado</option>
                                    <option class="textFont" value="1">Iniciado</option>
                                    <option class="textFont" value="2">Fechado</option>
                                    <option class="textFont" value="3">Desativado</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-1 textFont" style="width: 100%;border-top: 1px solid #000000;">
                            <label id="labelprincipal" name="labelprincipal" class="control-label mt-2 mb-0 textFont">Selecione
                                Responsável</label>
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="form-group row">
                                <div class="input-group input-group-sm col-sm-10">
                                    <div class="input-group-prepend mb-1 textFont">
                                        <div id="labelcnpjinep" name="labelcnpjinep" class="input-group-text textFont">
                                            <strong class="textFont">CNPJ</strong>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control textFont" id="codido" name="codigo" maxlength="18">
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-sm btn-primary textFont" role="button"
                                        onClick="carregarCodigo()" id="botaoobtercodigo" name="botaoobtercodigo">
                                        Obter</a>
                                </div>
                            </div>
                            <div class="input-group input-group-sm row col-sm-12">
                                <div class="input-group-prepend mb-2">
                                    <div class="input-group-text"><strong class="textFont">Razão</strong></div>
                                </div>
                                <input type="text" class="form-control textFont" style="background-color:  #FFFF;"
                                    id="razao" name="razao" readonly required>
                            </div>
                        </div>
                        <div class="form-group mt-1" style="width: 100%;border-top: 1px solid #000000;">
                            <label id="labelperiodo" name="labelperiodo" class="control-label mt-2 mb-0 textFont" >Informe o
                                Período</label>
                        </div>
                        <div class="row">
                            <div class="input-group input-group-sm row col-sm-12">
                                <div class="input-group-prepend mb-2 ml-2 textFont">
                                    <div class="input-group-text"><strong class="textFont">Inicial</strong></div>
                                </div>
                                <input type="date" class="form-control mr-2 textFont" placeholder="Inicial" required
                                    name="inicio" id="inicio">
                                <div class="input-group-prepend mb-2">
                                    <div class="input-group-text"><strong class="textFont">Final</strong></div>
                                </div>
                                <input type="date" class="form-control textFont" placeholder="Final" required name="fim"
                                    id="fim">
                            </div>
                        </div>
                        <div class="form-group mt-1" style="width: 100%;border-top: 1px solid #000000;">
                            <label id="labelperiodo" name="labelperiodo" class="control-label mt-2 mb-0 textFont">Informe o
                                Período de Adesão</label>
                        </div>
                        <div class="row">
                            <div class="input-group input-group-sm row col-sm-12">
                                <div class="input-group-prepend mb-2 ml-2">
                                    <div class="input-group-text"><strong class="textFont">Inicial</strong></div>
                                </div>
                                <input type="date" class="form-control mr-2 textFont" placeholder="Inicial" required
                                    name="iniadesao" id="iniadesao">
                                <div class="input-group-prepend mb-2 textFont">
                                    <div class="input-group-text"><strong class="textFont">Final</strong></div>
                                </div>
                                <input type="date" class="form-control textFont" placeholder="Final" required name="fimadesao"
                                    id="fimadesao">
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="tabbable textFont">
                                <ul class="nav-tabs nav textFont">
                                    <li class="nav-item textFont" >
                                        <a href="#tabpalavras" data-toggle="tab" class="nav-link active textFont" onclick="getElementById('palavras').focus()">Palavras</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#tabpseudos" data-toggle="tab" class="nav-link textFont" onclick="getElementById('pseudos').focus()">Pseudopalavras</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#tabtexto" data-toggle="tab" class="nav-link textFont" onclick="getElementById('texto').focus()">Texto</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#tabtreinamento" data-toggle="tab" class="nav-link textFont">Treinamento IA</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tabpalavras" class="tab-pane active container">
                                        <div class="form-group">
                                            <div class="input-group textFont">
                                                <textarea class="form-control mt-2 textFont" id="palavras" name="palavras" rows="4" cols="50"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="tabpseudos" class="tab-pane container">
                                        <div class="form-group">
                                            <div class="input-group textFont">
                                                <textarea class="form-control mt-2 textFont" id="pseudos" name="pseudos" rows="4" cols="50"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="tabtexto" class="tab-pane container">
                                        <div class="form-group textFont">
                                            <div class="input-group textFont">
                                                <textarea class="form-control mt-2 textFont" id="texto" name="texto" rows="4" cols="50"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="tabtreinamento" class="tab-pane container">
                                        <label id="labeltreinamento" name="labeltreinamento" class="control-label mt-2 mb-0 textFont">Selecione
                                            o Treinamento</label>
                                        <select class="form-select form-group-sm textFont" aria-label=".form-select-sm example"
                                            name="treinamento" id="treinamento">
                                            <option class="textFont" selected>Selecione...</option>
                                            <option class="textFont" value="0">Treino 1</option>
                                            <option class="textFont" value="1">Treino 2</option>
                                            <option class="textFont" value="2">Treino 3</option>
                                            <option class="textFont" value="3">Treino 4</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary textFont">Salvar</button>
                        <button type="cancel" class="btn btn-danger textFont" data-dismiss="modal">Cancelar</button>
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
                        $('#dlgTestes').modal({
                            show: true
                        });
                    });
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
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        $(document).ready(function() {

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

        function mudarLabelTaStatus() {
            if ($('#tastatus').is(':checked')) {
                $('#labeltastatus').val('Não aderiu');
            } else {
                $('#labeltastatus').val('Aderiu');
            }
        }

        function mudarPrincipal() {
            var select = document.getElementById("principal");
            var opcaoValor = select.options[select.selectedIndex].value;
            switch (opcaoValor) {
                case '0':
                    document.getElementById("labelprincipal").innerHTML = 'Selecione a Secretaria Responsável';
                    document.getElementById("labelcnpjinep").innerHTML = '<b>CNPJ</b>';
                    $('#sec_id').val(1);
                    $('#esc_id').val(0);
                    $('#razao').val('Secretaria Nacional de Alfabetização - SEALF');
                    document.getElementById("botaoobtercodigo").disabled = true;
                    break;
                case '1':
                case '2':
                    document.getElementById('labelprincipal').innerHTML = 'Selecione a Secretaria Responsável';
                    document.getElementById('labelcnpjinep').innerHTML = '<b>CNPJ</b>';
                    document.getElementById("botaoobtercodigo").disabled = false;
                    $('#razao').val('');
                    $('#esc_id').val(0);
                    break;
                case '3':
                    document.getElementById("labelprincipal").innerHTML = 'Selecione a Escola Responsável';
                    document.getElementById("labelcnpjinep").innerHTML = '<b>INEP</b>';
                    document.getElementById("botaoobtercodigo").disabled = false;
                    $('#sec_id').val(0);
                    $('#razao').val('');
                    break;
            }
        }

        function buscarCEP() {
            var texto = $("#cep").val().replace(/[^0-9]/g, '');
            if (texto == '') {
                alert("CEP Necessário para Pesquisa!");
                document.getElementById('cep').focus();
                return;
            }
            if (texto.length != 8) {
                alert("CEP Inválido!");
                document.getElementById('cep').focus();
                return;
            }

            pegaCEP(texto, function(data) {
                if (data.length == 0) {
                    alert("CEP não encontrado!");
                    document.getElementById('cep').focus();
                    return;
                }
                $('#cidade').val(data.localidade);
                $("#uf").val(data.uf);
                $("#logradouro").val(data.logradouro);
                $("#bairro").val(data.bairro);
                $("#cid_ibge").val(data.ibge);
            });

            document.getElementById('logradouro').focus();
        }

        function pegaCEP(codigo, callback) {
            var servidor = 'https://viacep.com.br/ws/' + codigo + '/json/';

            $.getJSON(servidor, function(data) {
                callback(data);
            });
        }

        function novoTeste() {
            document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Novo Teste</h5>';
            $('#id').val('');
            $('#sec_id').val('');
            $('#esc_id').val('');
            $('#razao').val('');
            $('#descricao').val('');
            $('#codigo').val('');
            document.getElementById("principal").selectedIndex=0;
            document.getElementById("status").selectedIndex=0;
            $('#inicio').val('');
            $('#fim').val('');
            $('#iniadesao').val('');
            $('#fimadesao').val('');
            $('#palavras').val('');
            $('#pseudos').val('');
            $('#texto').val('');
            $('#dlgTestes').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        }

        $("#dlgTestes").submit(function(event) {
            event.preventDefault();
            if ($("#id").val() != '') {
                salvarTeste();
            } else {
                criarTeste();
            }
            $("#dlgTestes").modal('hide');
        });

        function criarTeste() {
            var selprincipal = document.getElementById('principal');
            var selstatus = document.getElementById('status');
            var secid = $("#sec_id").val();
            var escid = $("#esc_id").val();
            principal = selprincipal.options[selprincipal.selectedIndex].value;
            if(principal==0){
                secid=1;
                escid=0;
            }else if (principal==1 || principal==2){
                escid=0;
            }else{
                secid=0;
            }
            teste = {
                descricao: $("#descricao").val(),
                sec_id: secid,
                esc_id: escid,
                principal: principal,
                status: selstatus.options[selstatus.selectedIndex].value,
                inicio: $("#inicio").val(),
                fim: $('#fim').val(),
                iniadesao: $('#iniadesao').val(),
                fimadesao: $('#fimadesao').val(),
                palavras: $('#palavras').val(),
                pseudos: $("#pseudos").val(),
                texto: $('#texto').val(),
                treinamento: 1
            };
            let url = '{{route('testes.store')}}';
            $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    data: teste,
                    success: function(data) {
                        teste = JSON.parse(data);
                        var html = '<button type="button" class="btn btn-sm btn-primary textFont" onclick="editar(' + teste.id +
                            ')">Editar</button>' + ' ';
                        html += '<button type="button" class="btn btn-sm btn-danger textFont" onclick="excluir(' + teste.id +
                            ')">Excluir</button>';
                        var t = $('#tabelaTestes').DataTable();
                        t.row.add({
                            "id": teste.id,
                            "tst_descricao": teste.tst_descricao,
                            "tst_principal": convNivel(teste.tst_principal),
                            "tst_ini": formataData(teste.tst_ini),
                            "tst_fim": formataData(teste.tst_fim),
                            "tst_status": convStatus(teste.tst_status),
                            "action": html
                        }).draw(false);
                    }
                })
                .done(function() {
                    Swal.fire("Teste Criado com Sucesso!",'','info');
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    Swal.fire("Falha ao Criar Teste!\n" + jqXHR.responseText,'','error');
                });
        }

        function editar(id) {
            let url = '{{route('testes.edit',':queryId')}}';
            url = url.replace(':queryId', id);
            $.getJSON(url, function(data) {
                teste = data;
                console.log(teste);
                document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Editar Teste</h5>';
                $('#id').val(teste[0].id);
                $('#sec_id').val(teste[0].tst_sec_id);
                $('#esc_id').val(teste[0].tst_esc_id);
                $('#descricao').val(teste[0].tst_descricao);
                document.getElementById("principal").selectedIndex=teste[0].tst_principal+1;
                document.getElementById("status").selectedIndex=teste[0].tst_status+1;
                $('#inicio').val(teste[0].tst_ini);
                $('#fim').val(teste[0].tst_fim);
                $('#iniadesao').val(teste[0].tst_data_iniadesao);
                $('#fimadesao').val(teste[0].tst_data_fimadesao);
                $('#palavras').val(teste[0].tst_palavras);
                $('#pseudos').val(teste[0].tst_pseudopalavras);
                $('#texto').val(teste[0].tst_texto);
                var principal = teste[0].tst_principal;
                if(principal<3){
                    $('#razao').val(teste[0].sec_razao);
                    $('#codigo').val(teste[0].sec_cnpj);
                }else{
                    $('#razao').val(teste[0].esc_razao);
                    $('#codigo').val(teste[0].esc_inep);
                }
                $('#dlgTestes').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });
        }

        function salvarTeste() {
            var selprincipal = document.getElementById('principal');
            var selstatus = document.getElementById('status');
            var secid = $("#sec_id").val();
            var escid = $("#esc_id").val();
            principal = selprincipal.options[selprincipal.selectedIndex].value;
            if(principal==0){
                secid=1;
                escid=0;
            }else if (principal==1 || principal==2){
                escid=0;
            }else{
                secid=0;
            }
            teste = {
                id: $('#id').val(),
                descricao: $("#descricao").val(),
                sec_id: secid,
                esc_id: escid,
                principal: principal,
                status: selstatus.options[selstatus.selectedIndex].value,
                inicio: $("#inicio").val(),
                fim: $('#fim').val(),
                iniadesao: $('#iniadesao').val(),
                fimadesao: $('#fimadesao').val(),
                palavras: $('#palavras').val(),
                pseudos: $("#pseudos").val(),
                texto: $('#texto').val(),
                treinamento: 1
            };
            let url = '{{route('testes.update',':queryId')}}';
            url = url.replace(':queryId', teste.id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "PUT",
                url: url,
                data: teste,
                success: function() {
                    var table = $('#tabelaTestes').DataTable();
                    table.row($(this).parents('tr')).draw(false);
                },
                error: function(error) {
                    Swal.fire("Ocorreu um erro ao alterar:\n" + error,'','error');
                }
            });
        }

        function excluir(id) {
            Swal.fire({
                title: 'Confirma a exclusão desse Teste?!',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não',
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '{{route('testes.destroy',':queryId')}}';
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
                            var table = $('#tabelaTestes').DataTable();
                            table.row($(this).parents('tr')).remove().draw(false);
                            Swal.fire('Teste excluído com sucesso!', '', 'success');
                        },
                        error: function(error) {
                            Swal.fire('Ocorreu um erro ao excluir:\n' + error, '', 'error');
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire('Exclusão não foi realizada!', '', 'info');
                }
            })

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

        function carregarTestes() {
            var tableId = "#tabelaTestes";

            if ($('#tabelaTestes').DataTable() != null) {
                $('#tabelaTestes').DataTable().clear();
                $('#tabelaTestes').DataTable().destroy();
            }

            $(tableId + " tbody").empty();

            let url = '{{route('testes.indexJSON')}}';

            $('#tabelaTestes').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": url,
                columnDefs: [{
                        "width": "8%",
                        "targets": [0, 3, 4]
                    },
                    {
                        "width": "26%",
                        "targets": 1
                    },
                    {
                        "width": "15%",
                        "targets": [2, 5]
                    },
                    {
                        "width": "30%",
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
                                '<input class="textFont" type="text" placeholder="Filtrar" style="width:300px"/>'
                            );
                        }
                        if (title === "Nível") {
                            $(this).html(
                                '<input class="textFont" type="text" placeholder="Filtrar" style="width:100px"/>');
                        }
                        if (title == "Status") {
                            $(this).html(
                                '<input class="textFont" type="text" placeholder="Filtrar" style="width:100px" />'
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

        $(function() {
            carregarTestes();
        });
    </script>
@endsection
