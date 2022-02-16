<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use stdClass;
use Tests\TestCase;

class UrlTest extends TestCase
{
    private stdClass $url;

    public function testIndex(): void
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('urls.create'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('urls.show', ['url' => $this->url->id]));

        $response->assertOk();
        $response->assertSee('Сайт: http://site1.com');
        $response->assertSee("<td>{$this->url->id}</td>", false);
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
        $showResponse->assertSee('Сайт: http://site4.com');
        $showResponse->assertSee("<td>{$id}</td>", false);

        $listResponse = $this->get(route('urls.index'));

        $listResponse->assertOk();
        $listResponse->assertSee('http://site4.com');
    }

    public function testStoreExistingUrl(): void
    {
        $storeResponse = $this->post(route('urls.store'), [
            'url' => [
                'name' => 'http://site1.com',
            ],
        ]);

        $uri = route('urls.show', ['url' => $this->url->id]);
        $storeResponse->assertRedirect($uri);

        $showResponse = $this->get($uri);
        $showResponse->assertSee('Страница уже существует');
    }

    protected function setUp(): void
    {
        parent::setUp();

        /** @var stdClass $url */
        $url = DB::table('urls')->where('name', 'http://site1.com')->first();
        $this->url = $url;
    }
}
