<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrlRequest;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
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

    public function store(StoreUrlRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $id = DB::table('urls')->insertGetId([
            'name'       => $validated['url']['name'],
            'created_at' => CarbonImmutable::now(),
        ]);

        flash('Страница успешно добавлена');

        return redirect()->route('urls.show', ['url' => $id]);
    }

    public function show($id): View
    {
        $url = DB::table('urls')->where('id', $id)->first();

        if (!$url) {
            abort(404);
        }

        return view('urls.show', ['url' => $url]);
    }
}
