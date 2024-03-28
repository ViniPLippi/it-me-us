@extends('layouts.principal', ['current' => 'importar'])
@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="card border tableProperties">
        <div class="card-header sectionTableHead">
            <h4><b class="textFont">Importar Alunos</b></h4>
        </div>
        <div class="card-body">
            <h5 class="card-title textFont" id="cardTitle"></h5>
            <div class="form-select w-60 mt-0 mb-2 pt-0">
                <input type="hidden" id="tipouser" name="tipouser" class="form-control textFont"
                    value="{{ Auth::user()->type }}">
                <input type="hidden" id="esc_id" name="esc_id" class="form-control textFont">
                <input type="hidden" id="arqret" name="arqret" class="form-control textFont">
                <label class="control-label textFont">Selecione a Escola</label>
                <div class="input-group">
                    <div class="form-group row">
                        <div class="input-group col-sm-7">
                            <div class="input-group-prepend">
                                <div class="input-group-text textFont"><strong>Código INEP</strong></div>
                            </div>
                            <input type="text" class="form-control textFont" id="inep" name="inep"
                                maxlength="8" autofocus>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-md btn-primary textFont" role="button" onClick="carregarEscola()">
                                Obter</a>
                        </div>
                    </div>
                </div>
                <div class="form-row justify-content-lg-left">
                    <div class="input-group col-sm-12">
                        <div class="input-group-prepend mb-2 textFont">
                            <div class="input-group-text textFont"><strong>Razão Social</strong></div>
                        </div>
                        <input type="text" class="form-control mb-2 textFont" style="background-color:  #FFFF;"
                            id="razao" name="razao" readonly>
                    </div>
                    <div class="input-group col-sm-10">
                        <div class="input-group-prepend textFont">
                            <div class="input-group-text textFont"><strong>Cidade</strong></div>
                        </div>
                        <input type="text" class="form-control textFont" style="background-color:  #FFFF;" id="cidade"
                            name="cidade" readonly>
                    </div>
                    <div class="input-group col-sm-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text textFont"><strong>UF</strong></div>
                        </div>
                        <input type="text" class="form-control textFont" style="background-color:  #FFFF;" id="uf"
                            name="uf" readonly>
                    </div>
                </div>
            </div>
            <form action="{{ route('alunos.fileimport') }}" method="POST" enctype="multipart/form-data" id="formImportar">
                @csrf
                <div class="form-group" id="divarquivo">
                    <label for="arquivo" id='labelarquivo' class="control-label"><i class="fa-solid fa-file-import"></i> Envio de Arquivo Excel</label>
                    <div class="input-group" id="divbotaoarquivo">
                        <input type="file" class="form-control" id="arquivo" name="arquivo" disabled accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel">
                    </div>
                </div>
            </form>
            <div class="form-group" id="divarquivodown">
                <label for="hrefarqret" class="control-label" id="labelarqret"><i class="fa-solid fa-file-arrow-down"></i> Arquivo
                    Retorno:</label>
                <div class="input-group" id="divarqret">
                    <a type="button" id="hrefarqret" class="btn btn-sm btn-outline-secondary"
                        href="/alunos/download" class="disabled">Download</a>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-primary textFont" role="button" id="botaoImportar" disabled>Importar</button>
            <button type="button" class="btn btn-secondary textFont" role="button" id="botaoCancelar" onclick="Cancelar()">Cancelar</a>
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

        $(document).ready(function() {

            /* Mask for Inep code */
            Inputmask("9{8}", {
                placeholder: "-",
                greedy: false
            }).mask('#inep');

        });

        function Mudarestado(el, estado) {
            var display = document.getElementById(el).style.display;
            if (estado)
                document.getElementById(el).style.display = 'block';
            else
                document.getElementById(el).style.display = 'none';
        }

        function Cancelar(){
            $('#esc_id').val('');
            $('#inep').val('');
            $('#razao').val('');
            $('#cidade').val('');
            $('#uf').val('');
            $('#arquivo').val('');
            document.getElementById("arquivo").disabled = true;
            document.getElementById("botaoImportar").disabled = true;
            Mudarestado('divarquivodown',false);
            return;
        }

        $("#botaoImportar").click(function(event) {
            event.preventDefault();

            var files = $('#arquivo')[0].files;

            const data = new FormData();

            if (files.length > 0) {
                data.append('arquivo', files[0]);
            }else{
                Swal.fire('Necessário Selecionar um Arquivo!', '', 'error');
                return;
            }

            data.append('id', $("#esc_id").val());

            let url = '{{route('alunos.fileimport')}}';
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                contentType: false,
                processData: false,
                datatype: 'json',
                success: function(data) {
                    console.log(data);
                    titulo = 'Planilha Processada com Sucesso!';
                    if(data.data.numerros==0){
                        titulo += '<br>Sem erros...';
                    }else{
                        titulo += '<br>'+data.data.numerros+' Erros encontrados!<br>Verifique planilha retorno...';
                    }
                    Swal.fire({
                        title: titulo,
                        icon: 'success',
                        showCloseButton: true,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText:
                          '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Ok',
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                    });
                    document.getElementById("arquivo").disabled = true;
                    document.getElementById("botaoImportar").disabled = true;
                    Mudarestado('divarquivodown',true);
                    document.getElementById("labelarqret").innerHTML =
                    '<label for="hrefarqret" id="labelarqret" class="control-label"><i class="fa-solid fa-file-arrow-down"></i> Arquivo Retorno: ' + data.data.file + '</label>';
                    let url = '{{route('alunos.download',[':queryId',':queryId2'])}}';
                    url = url.replace(':queryId', data.data.id);
                    url = url.replace(':queryId2', data.data.inep);
                    document.getElementById("hrefarqret").href = url;
                    $('#esc_id').val('');
                    $('#inep').val('');
                    $('#razao').val('');
                    $('#cidade').val('');
                    $('#uf').val('');
                    $('#arquivo').val('');
                    $('#arquivo').val('');
                },
                error: function(jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    var errorsHtml = '';
                    $.each(errors['errors'], function(index, value) {
                        errorsHtml +=
                            '<ul class="list-group"><li class="list-group-item alert alert-danger textFont">' +
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
                }
            });
        });

        function carregarEscola() {

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

                    document.getElementById("arquivo").disabled = false;
                    document.getElementById("botaoImportar").disabled = false;
                }
            }).fail(function() {
                Swal.fire('Escola não cadastrada!', '', 'info');
                document.getElementById('inep').focus();
            });
        }

        $(function() {
            Mudarestado('divarquivodown',false);
        });
    </script>
@endsection
