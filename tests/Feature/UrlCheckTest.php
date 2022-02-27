<?php

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
        $uri = route('urls.show', ['url' => $this->url->id]);

        $storeResponse = $this->post(route('urls.checks.store', ['url' => $this->url->id]));
        $storeResponse->assertRedirect($uri);

        $showResponse = $this->get($uri);
        $showResponse->assertOk();

        $id = DB::getPdo()->lastInsertId();
        /** @var stdClass $urlCheck */
        $urlCheck = DB::table('url_checks')->where('id', $id)->first();
        /** @var stdClass $url */
        $url = DB::table('urls')->where('id', $urlCheck->url_id)->first();
        $this->assertSame('http://site2.com', $url->name);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UrlSeeder::class);

        Http::fake([
            '*' => Http::response('Hello World', 200, ['Headers']),
        ]);

        /** @var stdClass $url */
        $url = DB::table('urls')->where('name', 'http://site2.com')->first();
        $this->url = $url;
    }
}
