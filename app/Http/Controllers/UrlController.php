<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    public function index(): object
    {
        $urls = DB::table('urls')->orderBy('id', 'asc')->get();
        $checks = DB::table('url_checks')->orderBy('url_id', 'asc')->orderBy('created_at', 'desc')->distinct('url_id')->get();
        $lastCheck = $checks->keyBy('url_id');
        return view('urls.index', compact('urls', 'lastCheck'));
    }
    public function show(int $id): object
    {
        $url = DB::table('urls')->find($id);
        $cheks = DB::table('url_checks')->where('url_id', '=', $id)->orderBy('updated_at', 'desc')->get();
        return view('urls.show', compact('url', 'cheks'));
    }
    public function store(Request $request): object
    {
        $formData = $request->input('url');
        $validator = Validator::make($formData, [
            'name' => 'required|url|max:255'
        ]);
        if ($validator->fails()) {
            flash('Не корректный адрес сайта!')->error();
            return redirect()->route('main');
        }

        $urlData = parse_url($formData['name']);
        $host = "{$urlData['scheme']}://{$urlData['host']}";

        $url = DB::table('urls')->where('name', $host)->first();
        if (! is_null($url)) {
            flash("Такой сайт \"{$url->name}\" уже существует!")->warning();
            $id = $url->id;
        } else {
            $id = DB::table('urls')->insertGetId([
                'name' => $host,
                'created_at' => Carbon::now()
            ]);
            flash('Сайт успешно добавлен!')->success();
        }
        return redirect()->route('urls.show', $id);
    }
}
