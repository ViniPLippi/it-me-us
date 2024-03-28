@extends('layouts.principal', ['current' => 'testeapiarq'])
@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <div class="card border tableProperties">
        <div class="card-header sectionTableHead">
            <h4><b class="textFont">Teste API Arquivos</b></h4>
        </div>
        <div class="card-body">
            <input type="hidden" id="tipouser" name="tipouser" class="form-control" value="{{ Auth::user()->type }}">
            </br>
            <h5 class="card-title">Teste</h5>
        </div>
        <form class="form-horizontal" id="formTeste" enctype="multipart/form-data">
            @csrf
            <div class="form-group" id="divid">
                <label for="id" class="control-label">ID</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="id" placeholder="ID" id="id"
                    >
                </div>
            </div>
            <div class="form-group" id="divarquivo">
                <label for="arquivo" class="control-label">Arquivos</label>
                <div class="input-group">
                    <input type="file" class="form-control" name="files" placeholder="Choose File" id="files"
                        multiple>
                </div>
            </div>
            <div class="card-footer sectionTableFooter">
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
        </form>
    </div>
@endsection

<!-------------------- Código Javascript --------------------------->

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#formTeste').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                let TotalFiles = $('#files')[0].files.length; //Total files
                let files = $('#files')[0];
                for (let i = 0; i < TotalFiles; i++) {
                    formData.append('files' + i, files.files[i]);
                }
                formData.append('TotalFiles', TotalFiles);
                formData.append('id', $('#id').val());
                url = '{{route('enviarArquivos')}}';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        this.reset();
                        alert('File has been uploaded successfully');
                        console.log(data);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
@endsection
