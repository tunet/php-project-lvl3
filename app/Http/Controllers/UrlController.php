<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrlRequest;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UrlController extends Controller
{
    public function index(): View
    {
        $urls = DB::table('urls')
            ->select('urls.*', 'url_checks.status_code', 'url_checks.created_at as check_created_at')
            ->leftJoin('url_checks', function (JoinClause $join) {
                $join->on('urls.id', '=', 'url_checks.url_id')
                    ->whereRaw(
                        <<<WHERE
                        url_checks.id IN (
                            select MAX(uc2.id)
                            from url_checks as uc2
                            join urls as u2 on u2.id = uc2.url_id
                            group by u2.id
                        )
                        WHERE
                    );
            })
            ->get();

        return view('urls.index', ['urls' => $urls]);
    }

    public function create(): View
    {
        return view('urls.create');
    }

    public function store(StoreUrlRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $url = DB::table('urls')->where('name', $validated['url']['name'])->first();

        if ($url) {
            flash('Страница уже существует')->error();

            return redirect()->route('urls.show', ['url' => $url->id]);
        }

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

        $checks = DB::table('url_checks')->where('url_id', $id)->get();

        return view('urls.show', [
            'url'    => $url,
            'checks' => $checks,
        ]);
    }
}
