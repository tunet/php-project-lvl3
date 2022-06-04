<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use DiDom\Document;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UrlCheckController extends Controller
{
    /**
     * @param int $id
     */
    public function store($id): RedirectResponse
    {
        $url = DB::table('urls')->find($id);

        abort_unless($url, 404);

        try {
            $response = Http::get($url->name);
        } catch (ConnectionException $exception) {
            flash($exception->getMessage())->error();

            return redirect()->route('urls.show', ['url' => $id]);
        }

        $document = new Document($response->getBody()->getContents());

        DB::table('url_checks')->insert([
            'url_id'      => $url->id,
            'status_code' => $response->status(),
            'created_at'  => CarbonImmutable::now(),
            'h1'          => optional($document->first('h1'))->text(),
            'title'       => optional($document->first('title'))->text(),
            'description' => optional($document->first('meta[name=description][content]'))->content,
        ]);

        flash('Страница успешно проверена');

        return redirect()->route('urls.show', ['url' => $id]);
    }
}
