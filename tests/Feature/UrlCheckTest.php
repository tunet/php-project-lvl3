<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlCheckTest extends TestCase
{
    use RefreshDatabase;

    public function testStore(): void
    {
        $url = DB::table('urls')->where('name', 'http://site2.com')->first();
        $this->assertNotEmpty($url);

        $uri = route('urls.show', ['url' => $url->id]);

        $storeResponse = $this->post(route('urls.checks.store', ['url' => $url->id]));
        $storeResponse->assertRedirect($uri);

        $id = DB::getPdo()->lastInsertId();

        $showResponse = $this->get($uri);
        $showResponse->assertSee("<td>{$id}</td>", false);
    }
}
