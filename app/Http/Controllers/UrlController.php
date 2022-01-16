<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UrlController extends Controller
{
    public function index(): View
    {
        $urls = DB::table('urls')->get();

        return view('urls.index', ['urls' => $urls]);
    }

    public function create(): View
    {
        return view('urls.create');
    }

    public function store(Request $request): View
    {
        //
    }

    public function show($id): View
    {
        //
    }

    public function edit($id): View
    {
        //
    }

    public function update(Request $request, $id): View
    {
        //
    }

    public function destroy($id): View
    {
        //
    }
}
