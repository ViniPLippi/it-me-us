@extends('layouts.principal', ["current" => "secretarias"])
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
        <h4><b class="textFont">Lista de Secretarias</b></h4>
    </div>
    <input type="hidden" id="tipouser" name="tipouser" class="form-control" value="{{ Auth::user()->type }}">
    <div class="card-body">
        <h5 class="card-title textFont" id="cardTitle"></h5>
        <table id="tabelaSecretarias" class="table table-striped textFont" style="width:100%">
            <thead class="tableHead">
                <tr>
                    <th class="textFont">Código</th>
                    <th class="textFont">CNPJ</th>
                    <th class="textFont">Razão Social</th>
                    <th class="textFont">Tipo</th>
                    <th class="textFont"><span class="colActions" >Ações</span></th>
                </tr>
            </thead>
            <tbody class="tBody tBodySecretaria">
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th class="textFont">CNPJ</th>
                    <th class="textFont">Razão Social</th>
                    <th class="textFont">Tipo</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="card-footer">
        <button class="btn btn-sm btn-primary textFont" role="button" onClick="novoSecretaria()">Nova Secretaria</a>
    </div>

</div>


<div class="modal" tabindex="-1" role="dialog" id="dlgSecretarias">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal textFont" id="formCidade" action="{{route('secretarias.store')}}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="modal-header" id="cabecform">
                    <h5 class="modal-title textFont">Nova Secretaria</h5>
                </div>
                <div class="modal-body textFont">
                    <input type="hidden" id="id" name="id" class="form-control textFont">
                    <div class="form-group textFont">
                        <label for="cnpj" class="control-label textFont">CNPJ</label>
                        <div class="input-group textFont">
                            <input type="text" class="form-control textFont" id="cnpj" name="cnpj" placeholder="CNPJ" required>
                        </div>
                    </div>

                    <div class="form-group textFont">
                        <label for="razao" class="control-label textFont">Razão Social</label>
                        <div class="input-group textFont">
                            <input type="text" class="form-control textFont" id="razao" name="razao" placeholder="Razão Social" required>
                        </div>
                    </div>

                    <div class="form-group textFont">
                        <label for="tipo" class="control-label textFont">Tipo de Secretária</label>
                        <div class="input-group textFont">
                            <select class="form-control textFont" id="tipo" name="tipo" placeholder="Tipo de Secretaria" required>
                                <option class="textFont" value="" selected>Selecione uma Opção</option>
                                <option class="textFont" value="1">Federal</option>
                                <option class="textFont" value="2">Estadual</option>
                                <option class="textFont" value="3">Municipal</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group textFont">
                        <label for="cep" class="control-label textFont">CEP</label>
                        <div class="input-group textFont">
                            <input type="text" class="form-control textFont" id="cep" name="cep" placeholder="CEP" onblur="buscarCEP(this.value)" required>
                            <!--<button class="btn btn-sm btn-primary" role="button" onClick="buscarCEP()"> Obter</a>-->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="textFont" for="Cidade">Cidade</label>
                                <input type="text" class="form-control textFont" placeholder="Cidade" required name="cidade" id="cidade" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="textFont" for="uf">UF</label>
                                <input type="text" class="form-control textFont" placeholder="UF" required name="uf" id="uf" readonly>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <label for="logradouro" class="control-label textFont">Logradouro</label>
                            <div class="input-group textFont">
                                <input type="text" class="form-control textFont" id="logradouro" name="logradouro" placeholder="Logradouro" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="numero" class="control-label textFont">Número</label>
                            <div class="input-group">
                                <input type="text" class="form-control textFont"  id="numero" name="numero" placeholder="Nº" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="complemento" class="control-label textFont">Complemento</label>
                        <div class="input-group">
                            <input type="text" class="form-control textFont" id="complemento" name="complemento" placeholder="Complemento" required>
                        </div>
                    </div>

                    <div hidden class="form-group">
                        <label for="cep" class="control-label textFont">Código IBGE</label>
                        <div class="input-group">
                            <input type="text" class="form-control textFont" id="idcid" name="idcid" placeholder="Identificar Cidade" required>
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
                $('#dlgSecretarias').modal({
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
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Access-Control-Allow-Origin': '*'
        }
    });

    $(document).ready(function() {

        /* Mask for CNPJ */
        Inputmask("9{2}.9{3}.9{3}/9{4}-9{2}", {
            placeholder: "-",
            greedy: false,
        }).mask('#cnpj');

        /* Mask for Zip code */
        Inputmask("9{5}-9{3}", {
            placeholder: "-",
            greedy: false
        }).mask('#cep');
    });

    function buscarCEP(texto) {
        var texto = $("#cep").val().replace(/[^0-9]/g, '');
        pegaCEP(texto, function(data) {
            if (data.length == 0) {
                alert("Município não encontrado com esse código!");
                document.getElementById('cep').focus();
                return;
            }
            $('#cidade').val(data.localidade);
            $("#uf").val(data.uf);
            $('#logradouro').val(data.logradouro);
            $('#idcid').val(data.ibge);
        });

        document.getElementById('logradouro').focus();
        document.getElementById('idcid').focus();
    }

    function pegaCEP(codigo, callback) {

        $.getJSON('https://viacep.com.br/ws/' + codigo + '/json/?callback=?', function(data) {
            callback(data);
        });
    }

    function formataCNPJ(cnpj){
        return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
    }

    function novoSecretaria() {
        document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Nova Secretaria</h5>';
        $('#id').val('');
        $('#cnpj').val('');
        $('#razao').val('');
        $('#tipo').val('');
        $('#logradouro').val('');
        $('#numero').val('');
        $('#complemento').val('');
        $('#cep').val('');
        $('#idcid').val('');
        $('#dlgSecretarias').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }

    $("#dlgSecretarias").submit(function(event) {
        event.preventDefault();
        if ($("#id").val() != '') {
            salvarSecretaria();
        } else {
            criarSecretaria();
        }
        $("#dlgSecretarias").modal('hide');
    });

    function criarSecretaria() {
        secretaria = {
            cnpj: $("#cnpj").val().replace(/[^\d]+/g, ''),
            razao: $("#razao").val(),
            tipo: $("#tipo").val(),
            logradouro: $("#logradouro").val(),
            numero: $("#numero").val(),
            complemento: $("#complemento").val(),
            cep: $("#cep").val().replace(/[^0-9]/g, ''),
            idcid: $("#idcid").val(),
        };
        let url = '{{route('secretarias.store')}}';
        //            url = url.replace(':queryId', calculatedId);
        $.ajaxSetup({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.post(url, secretaria, function(data) {
                secretaria = JSON.parse(data);
                var html = '<button class="btn btn-sm btn-primary textFont" onclick="editar(' + secretaria.id + ')">Editar</button>' + ' ';
                html += '<button class="btn btn-sm btn-danger textFont" onclick="excluir(' + secretaria.id + ')">Excluir</button>';
                var t = $('#tabelaSecretarias').DataTable();
                t.row.add({
                    "id": secretaria.id,
                    "sec_cnpj": secretaria.sec_cnpj,
                    "sec_razao": secretaria.sec_razao,
                    "sec_tipo": secretaria.sec_tipo,
                    "sec_logradouro": secretaria.sec_logradouro,
                    "sec_numero": secretaria.sec_numero,
                    "sec_complemento": secretaria.sec_complemento,
                    "sec_cep": secretaria.sec_cep,
                    "sec_cid_id": secretaria.sec_cid_id,
                    "action": html
                }).draw(false);
            })
            .done(function() {
                Swal.fire('Secretaria Cadastrada com Sucesso!', '', 'success');
            })
            .fail(function(jqXhr, json, errorThrown) { // this are default for ajax errors
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
        let url = '{{route('secretarias.edit',':queryId')}}';
        url = url.replace(':queryId', id);
        $.getJSON(url, function(data) {
            secretaria = data;
            document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Editar Secretaria</h5>';
            $("#id").val(secretaria[0].id);
            $("#cnpj").val(secretaria[0].sec_cnpj);
            $("#razao").val(secretaria[0].sec_razao);
            $("#tipo").val(secretaria[0].sec_tipo);
            $("#logradouro").val(secretaria[0].sec_logradouro);
            $("#numero").val(secretaria[0].sec_numero);
            $("#complemento").val(secretaria[0].sec_complemento);
            $("#cep").val(secretaria[0].sec_cep);
            $("#idcid").val(secretaria[0].sec_cid_id);
            $('#cidade').val(secretaria[0].cid_nome);
            $('#uf').val(secretaria[0].est_sigla);
            $('#dlgSecretarias').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });
    }

    function salvarSecretaria() {
        secretaria = {
            id: $("#id").val(),
            cnpj: $("#cnpj").val().replace(/[^\d]+/g, ''),
            razao: $("#razao").val(),
            tipo: $("#tipo").val(),
            logradouro: $("#logradouro").val(),
            numero: $("#numero").val(),
            complemento: $("#complemento").val(),
            cep: $("#cep").val().replace(/[^0-9]/g, ''),
            idcid: $("#idcid").val(),
        };
        let url = '{{route('secretarias.update',':queryId')}}';
        url = url.replace(':queryId', secretaria.id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "PUT",
            url: url,
            contentType: 'application/json',
            data: JSON.stringify(secretaria),
        }).done(function() {
            var table = $('#tabelaSecretarias').DataTable();
            table.row($(this).parents('tr')).draw(false);
            Swal.fire('Secretaria Atualizada com Sucesso!', '', 'success');
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

    function excluir(id) {
        var resultado = confirm("Deseja excluir a Secretaria?");
        if (resultado == false) {
            return;
        }
        let url = '{{route('secretarias.destroy',':queryId')}}';
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
                var table = $('#tabelaSecretarias').DataTable();
                table.row($(this).parents('tr')).remove().draw(false);
                Swal.fire("Cidade excluída com sucesso!",'','info');
            },
            error: function(error) {
                Swal.fire("Ocorreu um erro ao excluir:\n" + error,'','error');
            }
        });
    }

    function tipoSecretaria(tipo){
        if(tipo===1){
            return "Nacional";
        } else if (tipo===2){
            return "Estadual";
        }else {
            return "Municipal";
        }

        return "Não informado";
    }

    $(function() {
        let url = '{{route('secretarias.indexJSON')}}';
        $('#tabelaSecretarias').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": url,
            columnDefs: [
                {
                    targets: 1,
                    render: function(cnpj, type, row) {
                        return formataCNPJ(cnpj);
                    },
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return tipoSecretaria(data);
                    },
                }
            ],
            columns: [{
                    "data": "id"
                },
                {
                    "data": "sec_cnpj"
                },
                {
                    "data": "sec_razao"
                },
                {
                    "data": "sec_tipo"
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
                $('#tabelaSecretarias tfoot th').each(function() {
                    var title = $(this).text();
                    if (title === "CNPJ" || title === "Razão Social" || title == "Tipo") {
                        $(this).html('<input classs="textFont" type="text" placeholder="Filtrar ' + title +
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
