<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <title>@yield('title')</title>
</head>

<body>
    <div class="wrapper">
        <div class="content">
            <header>
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="{{ route('main') }}">Анализатор страниц</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="{{ route('main') }}">Главная</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('urls.index') }}">Сайты</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                @include('flash::message')
            </header>
            <main>
                 @yield('content')
            </main>
        </div>

        <footer class="border-top py-3 mt-5">
            <div class="container-lg">
                <div class="text-center">
                    <p>Drumsid</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>