<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use DiDom\Query;

class UrlController extends Controller
{
    public function index()
    {
        $urls = DB::table('urls')->orderBy('id', 'asc')->get();
        $checks = DB::table('url_checks')->orderBy('url_id', 'asc')->orderBy('created_at', 'desc')->distinct('url_id')->get();
        $lastCheck = $checks->keyBy('url_id');
        return view('urls.index', compact('urls', 'lastCheck'));
    }
    public function show($id)
    {
        $url = DB::table('urls')->find($id);
        $cheks = DB::table('url_checks')->where('url_id', '=', $id)->get();
        return view('urls.show', compact('url', 'cheks'));
    }
    public function store(Request $request)
    {
        $formData = $request->input('url');
        Validator::make($formData, [
            'name' => 'required|unique:urls' // unique будет работать если проверять только домен
        ])->validate();
        $urlData = parse_url($formData['name']);
        $host = $urlData['host'] ?? false;
        $duble = DB::table('urls')->where('name', $host)->first();
        if ($host && !$duble) {
            DB::table('urls')->insert([
                'name' => $urlData['scheme'] . "://" . $host,
                'created_at' => Carbon::now()
            ]);
            flash('Сайт успешно добавлен!')->success();
            return redirect()->route('urls.index');
        }
        if (! $host) {
            $message = "Введите корректный адрес сайта!";
        }
        if ($duble) {
            $message = "Такой сайт \"{$duble->name}\" уже существует!";
        }
        flash($message)->error();
        return view('main');
    }
    public function checks(Request $request, $id)
    {
        $url = DB::table('urls')->find($id);
        $check = Http::get($url->name);
        
        // $document = new Document($url->name, true);
        // dd($document);
        // dd($document->first('h1'));
        if ($check->ok()) {
            DB::table('url_checks')->insert([
                'url_id' => $id,
                'status_code' => $check->status(),
                'created_at' => $url->created_at,
                'updated_at' => Carbon::now()
            ]);
            DB::table('urls')->where('id', $id)->update(
                ['updated_at' => Carbon::now()]
            );
            flash('Сайт проанализирован!')->warning();
            return redirect()->route('urls.show', $id);
        }
        flash('проверка не удалась!')->error();
        return back();
    }
    public function destroy($id) // only for dev
    {
        if ($id) {
            DB::table('urls')->where('id', '=', $id)->delete();
            flash('сайт успешно удален!')->success();
        }
        return redirect()->route('urls.index');
    }
    public function checkDestroy($id) // only for dev
    {
        if ($id) {
            DB::table('url_checks')->where('id', '=', $id)->delete();
            flash('Данные о проверке удалены!')->success();
        }
        return back();
    }
}
