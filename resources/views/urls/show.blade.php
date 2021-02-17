@extends('layouts.app')

@section('title', 'Сайт')

@section('content')
    <div class="container-lg">
        <h2>Сайт: {{$url->name}}</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                    <tr>
                        <td>ID</td>
                        <td>{{$url->id}}</td>
                    </tr>
                    <tr>
                        <td>Имя</td>
                        <td>{{$url->name}}</td>
                    </tr>
                    <tr>
                        <td>Дата создания</td>
                        <td>{{$url->created_at}}</td>
                    </tr>
                    <tr>
                        <td>Дата обновления</td>
                        <td>{{$url->updated_at}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h3>Проверки</h3>
        {{Form::open(['url' => route('urls.checks.store', $url->id), 'method' => 'post'])}}
            {{Form::submit('Запустить проверку', ['class' => 'btn btn-primary'])}}
        {{Form::close()}}
        <br>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                    <tr>
                        <th>ID</th>				
                        <th>Код ответа</th>
                        <th>h1</th>
                        <th>keywords</th>
                        <th>description</th>
                        <th>Дата создания</th>
                    </tr>
                    @foreach ($cheks as $chek)
                    <tr>
                        <td>{{$chek->id}}</td>
                        <td>{{$chek->status_code}}</td>
                        <td>{{Str::limit($chek->h1, 20)}}</td>
                        <td>{{Str::limit($chek->keywords, 30)}}</td>
                        <td>{{Str::limit($chek->description, 30)}}</td>
                        <td>{{$chek->updated_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection