<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UrlCheckTest extends TestCase
{
    public function testStore(): void
    {
        /** @var \stdClass $url */
        $url = DB::table('urls')->where('name', 'http://site2.com')->first();
        $this->assertNotEmpty($url);

        $uri = route('urls.show', ['url' => $url->id]);

        $storeResponse = $this->post(route('urls.checks.store', ['url' => $url->id]));
        $storeResponse->assertRedirect($uri);

        $id = DB::getPdo()->lastInsertId();

        $showResponse = $this->get($uri);
        $showResponse->assertSee("<td>{$id}</td>", false);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*' => Http::response('Hello World', 200, ['Headers']),
        ]);
    }
}
