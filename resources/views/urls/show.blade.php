@extends('layouts.app')

<!-- Секция, содержимое которой обычный текст. -->
@section('title', 'Сайт')

@section('content')
    <div class="container-lg">
            <h2>Сайт {{$url->name}}</h2>
        </div>
    </div>
@endsection