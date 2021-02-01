<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;

class PageController extends Controller
{
    public function main()
    {
        $url = new Url();
        return view('main', compact('url'));
    }
}
