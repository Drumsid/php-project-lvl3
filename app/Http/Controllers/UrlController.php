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
        $validator = Validator::make($formData, [
            'name' => 'required|unique:urls|regex:/^(https?):\/\/[^ -"\s].+$/m' // unique будет работать если проверять только домен
        ]);
        if ($validator->fails()) {
            flash('пустой запрос')->error();
            return redirect()->route('main');
        }

        $urlData = parse_url($formData['name']);
        $host = array_key_exists('host', $urlData) ? "{$urlData['scheme']}://{$urlData['host']}" : null;
        if (! $host) {
            flash('Введите корректный адрес сайта!')->error();
            return redirect()->route('main');
        }

        $duble = DB::table('urls')->where('name', $host)->first();
        if ($duble) {
            flash("Такой сайт \"{$duble->name}\" уже существует!")->warning();
            return redirect()->route('urls.show', $duble->id);
        } else {
            $id = DB::table('urls')->insertGetId([
                'name' => $host,
                'created_at' => Carbon::now()
            ]);
            flash('Сайт успешно добавлен!')->success();
            return redirect()->route('urls.show', $id);
        }
    }
    public function checks(Request $request, $id)
    {
        $url = DB::table('urls')->find($id);
        try {
            $response = Http::get($url->name);
        } catch (\Exception $e) {
            flash("Error: {$e->getMessage()}")->error();
            return back();
        }
        $document = new Document($response->body());
        $h1 = optional($document->first('h1'))->text();
        $keywords = optional($document->first('meta[name=keywords]'))->getAttribute('content');
        $description = optional($document->first('meta[name=description]'))->getAttribute('content');
        if ($response->ok()) {
            DB::table('url_checks')->insert([
                'url_id' => $id,
                'status_code' => $response->status(),
                'h1' => $h1,
                'keywords' => $keywords,
                'description' => $description,
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
