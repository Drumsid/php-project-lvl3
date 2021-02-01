@extends('layouts.app')

<!-- Секция, содержимое которой обычный текст. -->
@section('title', 'Сайты')

@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайты</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Последняя проверка</th>
                        <th>Код ответа</th>
                    </tr>
                    @foreach ($urls as $url)
                    <tr>
                        <td>{{$url->id}}</td>
                        <td>
                            <a href="{{route('urls.show', $url)}}">{{$url->name}}</a>
                        </td>
                        <td>{{$url->created_at}}</td>
                        <td>200</td>
                    </tr>
                    @endforeach

                    
                </tbody>
            </table>
        </div>
    </div>
@endsection