<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;

class UrlController extends Controller
{
    public function index()
    {
        $urls = Url::paginate();
        $newUrl = new Url();
        return view('urls.index', compact('urls', 'newUrl'));
    }
    public function show($id)
    {
        $url = Url::findOrFail($id);
        return view('urls.show', compact('url'));
    }
    public function store(Request $request)
    {
        // dd($request);
        $data = $this->validate($request, [
            'name' => 'required',
        ]);

        $url = new Url();
        $url->fill($data);
        // $url->name = $request->input('name');
        $url->save();
        return redirect()->route('urls.index');
    }
}
