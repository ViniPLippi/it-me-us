<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FluAlfa') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }

        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>

<body  class="bodyPrincipal">
    <header class="headerSection">

        <div class="alignImgFluAlfa">
            <img src="{{ asset('img/logo-flu-alfa-sem-fundo.png') }}" class="imgHeaderFluAlfa" />

        </div>
         <div class="alignImgGov">
            <img src="{{ asset('img/Gov.br_logo.png') }}" class="imgHeaderGovBr" id="govBr"/>
        </div>
    </header>


    <div class="navBar">
        @component('componente_navbar',["current" => $current])
        @endcomponent
    </div>

    <div class=" container containerStyle">
        <main role="main">
            @hasSection ('content')
            @yield('content')
            @endif
        </main>
    </div>

    <footer>
        <div class="footerStyle">
            <span class="textFont textFooter">versão 2.0-{{ config('app.build', '00000') }}</span>
            <img src="{{ asset('img/MEC_logo_2013.png') }}" id="imgLogoMec" />
            <span class="textFont textFooter">Copyright©ITA 2022</span>
        </div>

    </footer>

<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>    <script src="https://use.fontawesome.com/15d60b599f.js"></script>
    <script src="https://kit.fontawesome.com/43d975ed0d.js" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js" defer type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js" defer type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js" defer type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.bootstrap5.min.js" defer type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" defer type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" defer type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" defer type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js" defer type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js" defer type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.colVis.min.js" defer type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    @hasSection ('javascript')
    @yield('javascript')
    @endif
</body>

</html>
