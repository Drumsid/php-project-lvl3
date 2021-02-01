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
                    <tr>
                        <td>1</td>
                        <td><a
                                href="#">https://www.notion.so</a>
                        </td>
                        <td>2021-01-28 07:43:47 </td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><a
                                href="#">https://phpstan.org</a>
                        </td>
                        <td>2021-01-28 07:44:01 </td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><a
                                href="#">https://www.deezer.com</a>
                        </td>
                        <td>2021-01-28 07:44:31 </td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><a
                                href="#">https://ru.wikipedia.org</a>
                        </td>
                        <td>2021-01-28 07:44:24 </td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><a
                                href="#">https://gitlab.com</a>
                        </td>
                        <td>2021-01-28 07:44:49 </td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td><a
                                href="#">https://ru.hexlet.io</a>
                        </td>
                        <td> </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td><a href="#">https://alza.cz</a>
                        </td>
                        <td>2021-01-28 21:34:54 </td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td><a
                                href="#">http://xn--80abig6bn3a1gc.xn--p1ai</a>
                        </td>
                        <td>2021-01-31 15:20:21 </td>
                        <td>403</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection