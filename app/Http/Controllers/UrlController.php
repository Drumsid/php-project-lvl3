<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Url;

class UrlController extends Controller
{
    public function index()
    {
        $urls = DB::table('urls')->orderBy('id', 'asc')->get();
        return view('urls.index', compact('urls'));
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
                'name' => $host,
                'created_at' => Carbon::now()
            ]);
            flash('Website has been successfully added!')->success();
            return redirect()->route('urls.index');
        }
        if (! $host) {
            $message = "Its not website addres!";
        }
        if ($duble) {
            $message = "This website \"{$duble->name}\" alredy exists!";
        }
        flash($message)->error();
        return view('main');
    }
    public function checks(Request $request, $id)
    {
        $url = DB::table('urls')->find($id);
        DB::table('url_checks')->insert([
            'url_id' => $id,
            'created_at' => $url->created_at,
            'updated_at' => Carbon::now()
        ]);
        DB::table('urls')->where('id', $id)->update(
            ['updated_at' => Carbon::now()]
        );
        return redirect()->route('urls.show', $id);
    }
    public function destroy($id) // only for dev
    {
        if ($id) {
            DB::table('urls')->where('id', '=', $id)->delete();
            flash('Website has been successfully deleted!')->success();
        }
        return redirect()->route('urls.index');
    }
}
