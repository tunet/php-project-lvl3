<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex(): void
    {
        $response = $this->get(route('urls.index'));

        $response->assertOk();
        $response->assertSee('http://site1.com');
        $response->assertSee('http://site2.com');
        $response->assertSee('http://site3.com');
    }

    public function testCreate(): void
    {
        $response = $this->get(route('urls.create'));

        $response->assertOk();
        $response->assertSee('<form ', false);
        $response->assertSee('method="post"', false);
        $response->assertSee(sprintf('action="%s"', route('urls.store')), false);
        $response->assertSee('name="url[name]"', false);
    }

    public function testShow(): void
    {
        $url = DB::table('urls')->where('name', 'http://site2.com')->first();

        $this->assertNotEmpty($url);

        $response = $this->get(route('urls.show', ['url' => $url->id]));

        $response->assertOk();
        $response->assertSee("Сайт: http://site2.com");
        $response->assertSee("<td>{$url->id}</td>", false);
    }

    public function testStore(): void
    {
        $storeResponse = $this->post(route('urls.store'), [
            'url' => [
                'name' => 'http://site4.com',
            ],
        ]);

        $id = DB::getPdo()->lastInsertId();
        $uri = route('urls.show', ['url' => $id]);

        $storeResponse->assertRedirect($uri);

        $showResponse = $this->get($uri);

        $showResponse->assertOk();
        $showResponse->assertSee("Сайт: http://site4.com");
        $showResponse->assertSee("<td>{$id}</td>", false);
    }
}
