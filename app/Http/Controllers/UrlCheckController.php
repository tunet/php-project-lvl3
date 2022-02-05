<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class UrlCheckController extends Controller
{
    public function store($id): RedirectResponse
    {
        $url = DB::table('urls')->where('id', $id)->first();

        if (!$url) {
            abort(404);
        }

        DB::table('url_checks')->insert([
            'url_id'     => $url->id,
            'created_at' => CarbonImmutable::now(),
        ]);

        flash('Страница успешно проверена');

        return redirect()->route('urls.show', ['url' => $id]);
    }
}
