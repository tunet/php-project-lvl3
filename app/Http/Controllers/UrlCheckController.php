<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UrlCheckController extends Controller
{
    public function store($id): RedirectResponse
    {
        $url = DB::table('urls')->where('id', $id)->first();

        if (!$url) {
            abort(404);
        }

        try {
            $response = Http::get($url->name);
        } catch (ConnectionException $exception) {
            flash($exception->getMessage())->error();

            return redirect()->route('urls.show', ['url' => $id]);
        }

        DB::table('url_checks')->insert([
            'url_id'      => $url->id,
            'status_code' => $response->status(),
            'created_at'  => CarbonImmutable::now(),
        ]);

        flash('Страница успешно проверена');

        return redirect()->route('urls.show', ['url' => $id]);
    }
}
