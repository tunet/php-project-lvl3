<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\UrlSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use stdClass;
use Tests\TestCase;

class UrlCheckTest extends TestCase
{
    private stdClass $url;

    public function testStore(): void
    {
        $html = file_get_contents(__DIR__ . '/../fixtures/index.html');
        Http::fake([
            '*' => Http::response($html, 200, ['Headers']),
        ]);

        $uri = route('urls.show', ['url' => $this->url->id]);

        $storeResponse = $this->post(route('urls.checks.store', ['url' => $this->url->id]));
        $storeResponse->assertRedirect($uri);

        $showResponse = $this->get($uri);
        $showResponse->assertOk();

        $this->assertDatabaseHas('url_checks', [
            'id'     => DB::getPdo()->lastInsertId(),
            'url_id' => $this->url->id,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UrlSeeder::class);

        $this->url = DB::table('urls')->where('name', 'http://site2.com')->get()->firstOrFail();
    }
}
