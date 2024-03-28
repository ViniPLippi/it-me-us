<link href="{{ asset('css/style.css') }}" rel="stylesheet">

<div id="app" class="navbarMain spaceFull">
    <nav class="navbar navbar-expand-sm navbar-light bg-white shadow-sm navbarItems">
        <div class="container-xl navbarItems">
            <!--  <a class="navbar-brand" href="{{ url('/') }}">
                  {{ config('app.name', 'FluAlfa') }}
              </a> -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse navbarItems sectionWitdh" id="navbarSupportedContent">
                <?php
                $tipo = -1;
                if (Auth::user() != null) {
                    $tipo = Auth::user()->type;
                }
                ?>
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto navBar textFont">
                    <li
                        @if ($current = 'home') class="nav-item active textFont" @else class="nav-item textFont" @endif>
                        <a class="nav-link textFont fontConfig " href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item dropdown spaceItem textFont">
                        @if (Auth::user() != null)
                            <a id="navbarDropdown" class="nav-link dropdown-toggle fontConfig textCad textFont"
                                href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" v-pre>
                                Cadastros
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item textFont" href="{{ route('estados.index') }}">
                                    Estados
                                </a>
                                <a class="dropdown-item textFont" href="{{ route('cidades.index') }}">
                                    Cidades
                                </a>

                                <a class="dropdown-item" href="{{ route('secretarias.index') }}">
                                    Secretarias
                                </a>

                                <a class="dropdown-item" href="{{ route('escolas.index') }}">
                                    Escolas
                                </a>

                                <a class="dropdown-item textFont" href="{{ route('alunos.index') }}">
                                    Alunos
                                </a>

                                <a class="dropdown-item textFont" href="{{ route('alunos.fileimportview') }}">
                                    Importar Alunos
                                </a>

                                <a class="dropdown-item textFont" href="{{ route('turmas.index') }}">
                                    Turmas
                                </a>
                                <a class="dropdown-item textFont" href="{{ route('testes.index') }}">
                                    Testes
                                </a>

                                <a class="dropdown-item textFont" href="{{ route('user.index') }}">
                                    Usuários
                                </a>

                                <a class="dropdown-item textFont" href="{{ route('testes.sala') }}">
                                    Sala Situação
                                </a>

                            </div>
                        @endif
                    </li>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto sectionUser">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item ">
                                <a class="nav-link textFont" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item textFont">
                                <a class="nav-link textFont" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown textFont">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle textFont" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</div>
