@extends('layouts.app')

@section('title', 'Сайты')

@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайты</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                    {{-- {{dd($urls)}} --}}
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
                            <a href="{{route('urls.show', $url->id)}}">{{$url->name}}</a>
                        </td>
                        <td>{{$url->updated_at}}</td>
                        <td>200 
                            {{Form::open(['url' => route('urls.destroy', $url->id), 'method' => 'delete'])}}
                                {{Form::submit('Del')}}
                            {{Form::close()}}
                        </td>

                    </tr>
                    @endforeach

                    
                </tbody>
            </table>
        </div>
    </div>
@endsection
