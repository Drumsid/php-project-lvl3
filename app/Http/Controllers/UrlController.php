<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Url;

class UrlController extends Controller
{
    public function index()
    {
        $urls = DB::table('urls')->get();
        return view('urls.index', compact('urls'));
    }
    public function show($id)
    {
        $url = DB::table('urls')->find($id);
        return view('urls.show', compact('url'));
    }
    public function store(Request $request)
    {
        // dd($request);
        // $validatedData = $request->validate([
        //     'name' => ['required']
        // ]);
        // dd($validatedData);
        $formData = $request->input('url');
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
    public function destroy($id)
    {
        if ($id) {
            DB::table('urls')->where('id', '=', $id)->delete();
            flash('Website has been successfully deleted!')->success();
        }
        return redirect()->route('urls.index');
    }
}
