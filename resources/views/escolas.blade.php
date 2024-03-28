@extends('layouts.principal', ['current' => 'escolas'])
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="card border tableProperties">
        <div class="card-header sectionTableHead">
            <h4><b class="textFont">Lista de Escolas</b></h4>
        </div>
        <input type="hidden" id="tipouser" name="tipouser" class="form-control" value="{{ Auth::user()->type }}">
        <div class="card-body">
            <h5 class="card-title textFont" id="cardTitle"></h5>
            <div class="form-select w-50 mt-0 mb-2 pt-0">
                <label for="estado" class="control-label">Selecione o Estado</label>
                <div class="input-group">
                    <select class="form-control textFont" id="estado" name="estado" placeholder="Selecione um Estado" width="50"
                        required>
                    </select>
                    <button class="btn btn-sm btn-primary btnGOV textFont ml-1" role="button" onClick="carregarEscolas()"> Obter</a>
                </div>
            </div>
            <table id="tabelaEscolas" class="table table-striped textFont" style="width:100%">
                <thead class="tableHead">
                    <tr>
                        <th class="textFont">Código</th>
                        <th class="textFont">Razão</th>
                        <th class="textFont">Cidade</th>
                        <th class="textFont">Código INEP</th>
                        <th class="textFont"><span >Ações</span></th>
                    </tr>
                </thead>
                <tbody class="tBodyEscola">
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th class="textFont">Razão</th>
                        <th class="textFont">Cidade</th>
                        <th class="textFont">INEP</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer sectionTableFooter">
            <button class="btn btn-sm btn-primary btnGOV textFont" role="button" id="botaoNovaEscola" onClick="novoEscola()" disabled>Nova
                Escola</a>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="dlgEscolas">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formescola" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header textFont" id="cabecform">
                        <h5 class="modal-title textFont">Nova Escola</h5>
                    </div>
                    <div class="modal-body mt-1">
                        <input class="inputEscolas textFont" type="hidden" id="id" name="id" class="form-control">
                        <input class="inputEscolas textFont" type="hidden" id="cid_id" name="cid_id" class="form-control">
                        <input class="inputEscolas textFont" type="hidden" id="cid_ibge" name="cid_ibge" class="form-control">

                        <div class="form-group textFont">
                            <label for="inep" class="control-label textFont">Código INEP</label>
                            <div class="input-group textFont">
                                <input type="text" class="form-control textFont" id="inep" name="inep" placeholder="Código INEP"
                                    maxlength="8">
                            </div>
                        </div>

                        <div class="form-group textFont">
                            <label for="razao" class="control-label textFont">Razão Social</label>
                            <div class="input-group textFont">
                                <input type="text" class="form-control textFont" id="razao" name="razao" placeholder="Razão Social"
                                    required>
                            </div>
                        </div>

                        <div class="form-group textFont">
                            <label for="telefone" class="control-label textFont">Telefone+DDD</label>
                            <div class="input-group textFont">
                                <input type="text" class="form-control textFont" id="telefone" name="telefone"
                                    placeholder="(DDD) Telefone">
                            </div>
                        </div>

                    </div>
                    <div class="form-group col-md-12">
                        <div class="tabbable">
                            <ul class="nav-tabs nav textFont">
                                <li class="nav-item textFont">
                                    <a href="#tabloc" data-toggle="tab" class="nav-link active textFont">Localização</a>
                                </li>
                                <li class="nav-item textFont">
                                    <a href="#tabcat" data-toggle="tab" class="nav-link textFont">Categorias</a>
                                </li>
                                <li class="nav-item textFont">
                                    <a href="#tabout" data-toggle="tab" class="nav-link textFont">Outras</a>
                                </li>
                                <li class="nav-item textFont">
                                    <a href="#tabsec" data-toggle="tab" class="nav-link textFont">Secretaria Educação</a>
                                </li>
                            </ul>
                            <div class="tab-content textFont">

                                <div id="tabloc" class="tab-pane active container textFont">
                                    <div class="form-group textFont">
                                        <div class="form-check form-check-inline textFont" style="margin-top: 15px;">
                                            <input class="form-check-input textFont" type="radio" name="localizacao" id="locurbana"
                                                value="1">
                                            <label class="form-check-label textFont" for="locurbana">Urbana</label>
                                        </div>
                                        <div class="form-check form-check-inline textFont">
                                            <input class="form-check-input textFont" type="radio" name="localizacao" id="locrural"
                                                value="2">
                                            <label class="form-check-label textFont" for="locrural">Rural</label>
                                        </div>
                                    </div>
                                    <label for="cep" class="control-label textFont">CEP</label>
                                    <div class="input-group">
                                        <div class="col-xs-2 textFont">
                                            <input class="form-control textFont" type="text" class="form-control" id="cep"
                                                name="cep" placeholder="CEP(99999-999)">
                                        </div>
                                        <div class="input-group-btn textFont">
                                            <button type="button" class="btn btn-sm btn-primary textFont" role="button"
                                                style="margin-left: 15px;" onClick="buscarCEP()">Obter</button>
                                        </div>
                                    </div>

                                    <div class="form-group textFont">
                                        <label for="logradouro" class="control-label textFont"
                                            style="margin-top: 15px;">Logradouro</label>
                                        <div class="input-group textFont">
                                            <input type="text" class="form-control textFont" id="logradouro" name="logradouro"
                                                placeholder="Logradouro">
                                        </div>
                                    </div>

                                    <div class="form-group textFont">
                                        <label for="bairro" class="control-label textFont">Bairro</label>
                                        <div class="input-group textFont">
                                            <input type="text" class="form-control textFont" id="bairro" name="bairro"
                                                placeholder="Bairro">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group textFont">
                                                <label for="Cidade" class="textFont">Cidade</label>
                                                <input type="text" class="form-control textFont" placeholder="Cidade" required
                                                    name="cidade" id="cidade" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2 textFont">
                                            <div class="form-group textFont">
                                                <label for="uf" class="textFont">UF</label>
                                                <input type="text" class="form-control textFont" placeholder="UF" required
                                                    name="uf" id="uf" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group textFont">
                                                <label for="latitude" class="textFont">Latitude</label>
                                                <input type="number" class="form-control textFont" placeholder="Latitude" required
                                                    name="latitude" id="latitude">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group textFont">
                                                <label for="longitude" class="textFont">Longitude</label>
                                                <input type="text" class="form-control textFont" placeholder="Longitude" required
                                                    name="longitude" id="longitude">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="tabcat" class="tab-pane container textFont">
                                    <label for="restricao" class="control-label textFont"
                                        style="margin-top: 15px;">Restrição</label>
                                    <div class="form-group textFont">
                                        <div class="form-check form-check-inline textFont">
                                            <input class="form-check-input textFont" type="radio" name="restricao" id="restricaosem"
                                                value="1">
                                            <label class="form-check-label textFont" for="restricaosem">1- Sem restrição de
                                                atendimento</label>
                                        </div>
                                        <div class="form-check form-check-inline textFont">
                                            <input class="form-check-input textFont" type="radio" name="restricao" id="restricaocom"
                                                value="2">
                                            <label class="form-check-label textFont" for="restricaocom">2- Escola atende
                                                exclusivamente alunos com deficiência</label>
                                        </div>
                                    </div>

                                    <label for="locdif" class="control-label textFont">Localidade Diferenciada</label>
                                    <div class="form-group textFont">
                                        <select class="form-select form-select-sm textFont" aria-label=".form-select-sm example"
                                            name="locdif" id="locdif">
                                            <option class="textFont" selected>Selecione uma dos opções</option>
                                            <option class="textFont" value="1">A escola não está em área de localização diferenciada</option>
                                            <option class="textFont" value="2">Área de Assentamento</option>
                                            <option class="textFont" value="3">Terra indígena</option>
                                            <option class="textFont" value="4">Área remanescente de quilombos</option>
                                        </select>
                                    </div>

                                    <label for="catadm" class="control-label textFont">Categoria Administrativa</label>
                                    <div class="form-group textFont">
                                        <div class="form-check form-check-inline textFont">
                                            <input class="form-check-input textFont" type="radio" name="catadm" id="catadmpublica"
                                                value="1">
                                            <label class="form-check-label textFont" for="catadmpublica">1- Pública</label>
                                        </div>
                                        <div class="form-check form-check-inline textFont">
                                            <input class="form-check-input textFont" type="radio" name="catadm"
                                                id="catadmparticular" value="2">
                                            <label class="form-check-label textFont" for="catadmparticular">2- Particular</label>
                                        </div>
                                    </div>

                                    <label for="depadm" class="control-label textFont">Dependência Administrativa</label>
                                    <div class="form-group">
                                        <select class="form-select form-select-sm textFont" aria-label=".form-select-sm example"
                                            name="depadm" id="depadm">
                                            <option class="textFont" selected>Selecione uma dos opções</option>
                                            <option class="textFont" value="1">Municipal</option>
                                            <option class="textFont" value="2">Estadual</option>
                                            <option class="textFont" value="3">Federal</option>
                                        </select>
                                    </div>

                                    <label for="convpodpub" class="control-label textFont">Conveniada ao Poder Público</label>
                                    <div class="form-group textFont">
                                        <div class="form-check form-check-inline textFont">
                                            <input class="form-check-input textFont" type="radio" name="convpodpub"
                                                id="convpodpubsim" value="1">
                                            <label class="form-check-label textFont" for="convpodpubsim">1- Sim</label>
                                        </div>
                                        <div class="form-check form-check-inline textFont">
                                            <input class="form-check-input textFont" type="radio" name="convpodpub"
                                                id="convpodpugnao" value="2">
                                            <label class="form-check-label textFont" for="convpodpubnao">2- Não</label>
                                        </div>
                                    </div>

                                    <label for="regconsedu" class="control-label textFont">Regulamentação pelo Conselho de
                                        Educação</label>
                                    <div class="form-group textFont">
                                        <div class="form-check form-check-inline textFont">
                                            <input class="form-check-input textFont" type="radio" name="regconsedu"
                                                id="regconsedusim" value="1">
                                            <label class="form-check-label textFont" for="regconsedusim">1- Sim</label>
                                        </div>
                                        <div class="form-check form-check-inline textFont">
                                            <input class="form-check-input textFont" type="radio" name="regconsedu"
                                                id="regconsedunao" value="2">
                                            <label class="form-check-label textFont" for="regconsedunao">2- Não</label>
                                        </div>
                                        <div class="form-check form-check-inline textFont">
                                            <input class="form-check-input textFont" type="radio" name="regconsedu"
                                                id="regconsedutra" value="2">
                                            <label class="form-check-label textFont" for="regconsedutra">3- Em tramitação</label>
                                        </div>
                                    </div>

                                </div>

                                <div id="tabout" class="tab-pane container textFont">Outras informações

                                    <div class="form-group textFont">
                                        <label for="porte" class="control-label textFont">Porte</label>
                                        <div class="input-group textFont">
                                            <input type="text" class="form-control textFont" id="porte" name="porte"
                                                placeholder="Porte">
                                        </div>
                                    </div>

                                    <div class="form-group textFont">
                                        <label for="etamodensofe" class="control-label textFont">Etapas e Modalidade de Ensino
                                            Oferecidas</label>
                                        <div class="input-group textFont">
                                            <input type="text" class="form-control textFont" id="etamodensofe" name="etamodensofe"
                                                placeholder="Etapas e Modalidade de Ensino Oferecidas">
                                        </div>
                                    </div>

                                    <div class="form-group textFont">
                                        <label for="outofeens" class="control-label textFont">Outras Ofertas Educacionais</label>
                                        <div class="input-group textFont">
                                            <input type="text" class="form-control textFont" id="outofeens" name="outofeens"
                                                placeholder="Outras Ofertas Educacionais">
                                        </div>
                                    </div>
                                </div>

                                <div id="tabsec" class="tab-pane container textFont">Secretaria Educação

                                    <div class="form-group textFont">
                                        <div class="input-group textFont">
                                            <input type="text" class="form-control textFont" id="secretaria" name="secretaria"
                                                placeholder="Secretaria" readonly>
                                        </div>
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
                        $('#dlgEscolas').modal({
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

        function mudarLabelTaStatus() {
            if ($('#tastatus').is(':checked')) {
                $('#labeltastatus').val('Não aderiu');
            } else {
                $('#labeltastatus').val('Aderiu');
            }
        }

        function buscarCEP() {
            var texto = $("#cep").val().replace(/[^0-9]/g, '');
            if (texto == '') {
                Swal.fire("CEP Necessário para Pesquisa!",'',"error");
                document.getElementById('cep').focus();
                return;
            }
            if (texto.length != 8) {
                Swal.fire("CEP Inválido!",'',"error");
                document.getElementById('cep').focus();
                return;
            }

            pegaCEP(texto, function(data) {
                if (data.length == 0) {
                    Swal.fire("CEP não encontrado!",'',"error");
                    document.getElementById('cep').focus();
                    return;
                }
                $('#cidade').val(data.localidade);
                $("#uf").val(data.uf);
                $("#logradouro").val(data.logradouro);
                $("#bairro").val(data.bairro);
                $("#cid_ibge").val(data.ibge);

                let estados = {
                    SP: 1,
                    RJ: 2,
                    ES: 3,
                    MG: 4,
                    BA: 5,
                    SE: 6,
                    PE: 7,
                    AL: 8,
                    PB: 9,
                    RN: 10,
                    CE: 11,
                    PI: 12,
                    MA: 13,
                    PA: 14,
                    AP: 15,
                    AM: 16,
                    RR: 17,
                    AC: 18,
                    DF: 19,
                    GO: 20,
                    RO: 21,
                    TO: 22,
                    MT: 23,
                    MS: 24,
                    PR: 25,
                    SC: 26,
                    RS: 27
                };                
                var ufInput = $("#uf").val();
                var ufValue = parseInt(estados[ufInput], 10);

                let estadoSelect = document.getElementById('estado').value;

                if (parseInt(estadoSelect, 10) !== ufValue) {
                    Swal.fire("Necessário colocar a UF do Estado selecionado previamente!", "", "info");
                    //ufInput.val("");
                    $("#uf").val("");
                    $('#cidade').val("");
                    $("#logradouro").val("");
                    $("#bairro").val("");
                    $("#cid_ibge").val("");
                    ufInput.focus();
                    return;
                }
            });

            document.getElementById('logradouro').focus();
        }

        function pegaCEP(codigo, callback) {
            var servidor = 'https://viacep.com.br/ws/' + codigo + '/json/';

            $.getJSON(servidor, function(data) {
                callback(data);
            });
        }

        function novoEscola() {
            document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Nova Escola</h5>';
            document.getElementById("locurbana").checked = true;
            document.getElementById("restricaosem").checked = true;
            document.getElementById("catadmpublica").checked = true;
            document.getElementById("convpodpubsim").checked = true;
            document.getElementById("regconsedusim").checked = true;
            document.getElementById("inep").disabled = false;
            $('#id').val('');
            $('#razao').val('');
            $('#inep').val('');
            $('#logradouro').val('');
            $('#telefone').val('');
            $('#porte').val('');
            $('#etamodensofe').val('');
            $('#outofeens').val('');
            $('#latitude').val('');
            $('#longitude').val('');
            $('#cep').val('');
            $('#cid_id').val('');
            $('#cidade').val('');
            $('#uf').val('');
            $('#bairro').val('');
            $('#dlgEscolas').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        }

        $("#dlgEscolas").submit(function(event) {
            event.preventDefault();
            if ($("#id").val() != '') {
                salvarEscola();
            } else {
                criarEscola();
            }
            $("#dlgEscolas").modal('hide');
        });

        function criarEscola() {
            var sellocdif = document.getElementById('locdif');
            var seldepadm = document.getElementById('depadm');            
            escola = {
                razao: $("#razao").val(),
                inep: $("#inep").val(),
                localizacao: document.querySelector('input[name="localizacao"]:checked').value,
                logradouro: $("#logradouro").val(),
                telefone: $("#telefone").val(),
                restricao: document.querySelector('input[name="restricao"]:checked').value,
                localdif: sellocdif.options[sellocdif.selectedIndex].value,
                catadm: document.querySelector('input[name="catadm"]:checked').value,
                depadm: seldepadm.options[seldepadm.selectedIndex].value,
                catescpriv: 1,
                convpodpub: document.querySelector('input[name="convpodpub"]:checked').value,
                regconsedu: document.querySelector('input[name="regconsedu"]:checked').value,
                porte: $("#porte").val(),
                etamodensofe: $('#etamodensofe').val(),
                outofeens: $('#outofeens').val(),
                latitude: $('#latitude').val(),
                longitude: $('#longitude').val(),
                cep: $("#cep").val().replace(/[^0-9]/g, ''),
                cid_ibge: $('#cid_ibge').val(),
                bairro: $('#bairro').val()
            };
            let url = '{{route('escolas.store')}}';
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                data: escola,
                success: function(data) {
                    escola = JSON.parse(data);
                    console.log(escola.id);
                    console.log(escola.esc_razao);
                    var html = '<button type="button" class="btn btn-sm btn-primary textFont" onclick="editar(' + escola
                        .id +
                        ')">Editar</button>' + ' ';
                    html += '<button type="button" class="btn btn-sm btn-danger textFont" onclick="excluir(' + escola
                        .id +
                        ')">Excluir</button>';
                    var t = $('#tabelaEscolas').DataTable();
                    t.row.add({
                        "id": escola.id,
                        "esc_razao": escola.esc_razao,
                        "cid_nome": escola.cid_nome,
                        "esc_inep": escola.esc_inep,
                        "action": html
                    }).draw(false);
                }
            }).done(function() {
                Swal.fire("Escola Cadastrada com Sucesso!",'',"info");
            }).fail(function(jqXhr, json, errorThrown) {
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
                    title: 'Erros encontrados!'
                });
            });
        }

        function editar(id) {
            let url = '{{route('escolas.edit',':queryId')}}';
            url = url.replace(':queryId', id);
            $.getJSON(url, function(data) {
                escola = data;
                document.getElementById("cabecform").innerHTML = '<h5 class="modal-title textFont">Editar escola</h5>';
                document.getElementById("inep").disabled = true;
                $('#id').val(escola[0].id);
                $('#razao').val(escola[0].esc_razao);
                $('#inep').val(escola[0].esc_inep);
                $('#logradouro').val(escola[0].esc_logradouro);
                $('#telefone').val(escola[0].esc_telefone);
                localizacao = document.getElementsByName('localizacao');
                localizacao[escola[0].esc_localizacao - 1].checked = true;
                restricao = document.getElementsByName('restricao');
                restricao[escola[0].esc_restricao - 1].checked = true;
                $("#locdif option").filter(function() {
                    return this.value == escola[0].esc_local_dif;
                }).attr('selected', true);
                catadm = document.getElementsByName('catadm');
                catadm[escola[0].esc_cat_adm - 1].checked = true;
                $("#depadm option").filter(function() {
                    return this.value == escola[0].esc_dep_adm;
                }).attr('selected', true);
                convpodpub = document.getElementsByName('convpodpub');
                convpodpub[escola[0].esc_conv_pod_pub - 1].checked = true;
                regconsedu = document.getElementsByName('regconsedu');
                regconsedu[escola[0].esc_reg_cons_edu - 1].checked = true;
                $('#porte').val(escola[0].esc_porte);
                $('#etamodensofe').val(escola[0].esc_eta_mod_ens_ofe);
                $('#outofeens').val(escola[0].esc_out_ofe_ens);
                $('#latitude').val(escola[0].esc_latitude);
                $('#longitude').val(escola[0].esc_longitude);
                $('#cep').val(escola[0].esc_cep);
                $('#cid_ibge').val(escola[0].cid_ibge);
                $('#bairro').val(escola[0].esc_bairro);
                $('#cidade').val(escola[0].cid_nome);
                $('#uf').val(escola[0].est_sigla);

                $('#dlgEscolas').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });
        }

        function salvarEscola() {
            var sellocdif = document.getElementById('locdif');
            var seldepadm = document.getElementById('depadm');
            escola = {
                id: $("#id").val(),
                razao: $("#razao").val(),
                inep: $("#inep").val(),
                localizacao: document.querySelector('input[name="localizacao"]:checked').value,
                logradouro: $("#logradouro").val(),
                telefone: $("#telefone").val(),
                restricao: document.querySelector('input[name="restricao"]:checked').value,
                localdif: sellocdif.options[sellocdif.selectedIndex].value,
                catadm: document.querySelector('input[name="catadm"]:checked').value,
                depadm: seldepadm.options[seldepadm.selectedIndex].value,
                catescpriv: 1,
                convpodpub: document.querySelector('input[name="convpodpub"]:checked').value,
                regconsedu: document.querySelector('input[name="regconsedu"]:checked').value,
                porte: $("#porte").val(),
                etamodensofe: $('#etamodensofe').val(),
                outofeens: $('#outofeens').val(),
                latitude: $('#latitude').val(),
                longitude: $('#longitude').val(),
                cep: $("#cep").val().replace(/[^0-9]/g, ''),
                cid_ibge: $('#cid_ibge').val(),
                bairro: $('#bairro').val()
            };
            let url = '{{route('escolas.update',':queryId')}}';
            url = url.replace(':queryId', escola.id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "PUT",
                url: url,
                data: escola,
                success: function() {
                    var table = $('#tabelaEscolas').DataTable();
                    table.row($(this).parents('tr')).draw(false);
                },
                error: function(error) {
                    Swal.fire("Ocorreu um erro ao alterar:\n" + error,'',"error");
                }
            });
        }

        function excluir(id) {
            Swal.fire({
                title: 'Confirma a exclusão dessa Escola?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    let url = '{{route('escolas.destroy',':queryId')}}';
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
                            var table = $('#tabelaEscolas').DataTable();
                            table.row($(this).parents('tr')).remove().draw(false);
                            Swal.fire('Escola excluída com sucesso!', '', 'success');
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

        function carregarEscolas() {
            if ($("#estado").val() == null) {
                Swal.fire("Necessário selecionar um Estado",'',"info");
                return;
            }

            document.getElementById("botaoNovaEscola").disabled = false;

            var tableId = "#tabelaEscolas";

            if ($('#tabelaEscolas').DataTable() != null) {
                $('#tabelaEscolas').DataTable().clear();
                $('#tabelaEscolas').DataTable().destroy();
            }

            //2nd empty html
            $(tableId + " tbody").empty();
            //            $(tableId + " thead").empty();
            let url = '{{route('escolas.indexJSON',':queryId')}}';
            url = url.replace(':queryId',$("#estado").val());
            $('#tabelaEscolas').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": url,
                "columnDefs": [{
                        "width": "10%",
                        "targets": 0
                    },
                    {
                        "width": "40%",
                        "targets": 1
                    },
                    {
                        "width": "5%",
                        "targets": 2
                    },
                    {
                        "width": "60%",
                        "targets": 4
                    }
                ],
                columns: [{
                        "data": "id"
                    },
                    {
                        "data": "esc_razao"
                    },
                    {
                        "data": "cid_nome"
                    },
                    {
                        "data": "esc_inep"
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
                    $('#tabelaEscolas tfoot th').each(function() {
                        var title = $(this).text();
                        if (title === "Razão") {
                            $(this).html(
                                '<input class="textFont" type="text" placeholder="Filtrar Razão" />'
                            );
                        }
                        if (title === "Cidade") {
                            $(this).html('<input class="textFont" type="text" placeholder="Filtrar Cidade" />');
                        }
                        if (title == "INEP") {
                            $(this).html(
                                '<input class="textFont" type="text" placeholder="Filtrar Código INEP" />'
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

        function carregarEstados() {
            let url = '{{route('estados.indexJsonA')}}';
            $.get(url, function(data) {
                opcao = '<option class="textFont" value = "-1" data-valor="TT"> Todos os Estados</option>';
                $('#estado').append(opcao);
                for (i = 0; i < data.length; i++) {
                    opcao = '<option class="textFont" value = "' + data[i].id + '" data-valor="' + data[i].sigla + '">' +
                        data[i]
                        .est_nome + '</option>';
                    $('#estado').append(opcao);
                }
                document.getElementById('estado').value = -1;
            });
        }

        $(function() {
            carregarEstados();
        });
    </script>
@endsection
