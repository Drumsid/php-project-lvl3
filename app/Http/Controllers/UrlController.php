<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

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
            'name' => 'required|unique:urls|regex:/^(https?):\/\/[^ -"\s].+$/m'
        ]);
        if ($validator->fails()) {
            flash('Не корректный адрес сайта!')->error();
            return redirect()->route('main');
        }

        $urlData = parse_url($formData['name']);
        if (array_key_exists('host', $urlData)) {
            $host = "{$urlData['scheme']}://{$urlData['host']}";
        } else {
            flash('Не корректный адрес сайта!')->error();
            return redirect()->route('main');
        }

        $duble = DB::table('urls')->where('name', $host)->first();
        if (! is_null($duble)) {
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
    public function checks(int $id): object
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
}
