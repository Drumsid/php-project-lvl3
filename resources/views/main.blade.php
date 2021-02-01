@extends('layouts.app')

<!-- Секция, содержимое которой обычный текст. -->
@section('title', 'Главная')

@section('content')
    <div class="container-fluid bg-dark pt-5 pb-5">
        <div class="container-lg">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-8 mx-auto text-white">
                    <h1 class="display-3 fs-1">Анализатор страниц</h1>
                    <p class="lead fs-5">Бесплатно проверяйте сайты на SEO пригодность</p>
                    <form action="" method="post"
                        class="d-flex justify-content-center flex-column flex-md-row">
                        <input type="hidden" name="_token" value="">
                        <input type="text" name="url[name]" value=""
                            class="form-control form-control-lg d-block d-md-block mb-3 mb-md-0"
                            placeholder="https://www.example.com">
                        <button type="submit"
                            class="btn btn-lg btn-primary ms-md-3 px-5 text-uppercase">Проверить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection