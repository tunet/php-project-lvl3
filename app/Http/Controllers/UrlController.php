<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrlRequest;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

use function compact;

class UrlController extends Controller
{
    public function index(): View
    {
        /** @var \Illuminate\Pagination\AbstractPaginator $urls */
        $urls = DB::table('urls')->paginate(10);

        $urlChecks = DB::table('url_checks')
            ->distinct('url_id')
            ->whereIn('url_id', $urls->getCollection()->pluck('id'))
            ->orderByDesc('url_id')
            ->orderByDesc('created_at')
            ->get()
            ->keyBy('url_id');

        return view('urls.index', compact('urls', 'urlChecks'));
    }

    public function create(): View
    {
        return view('urls.create');
    }

    public function store(StoreUrlRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $url = DB::table('urls')->where('name', $validated['url']['name'])->first();

        if (null !== $url) {
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

    /**
     * @param int $id
     */
    public function show($id): View
    {
        $url = DB::table('urls')->where('id', $id)->first();

        if (null === $url) {
            abort(404);
        }

        $checks = DB::table('url_checks')->where('url_id', $id)->get();

        return view('urls.show', [
            'url'    => $url,
            'checks' => $checks,
        ]);
    }
}
