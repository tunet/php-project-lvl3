<?php

namespace Tests\Feature;

use Database\Seeders\UrlSeeder;
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

        /** @var stdClass $url */
        $url = DB::table('urls')->where('id', $id)->first();
        $this->assertSame('http://site4.com', $url->name);

        $storeResponse->assertRedirect($uri);

        $showResponse = $this->get($uri);
        $showResponse->assertOk();
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
        $showResponse->assertOk();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UrlSeeder::class);

        /** @var stdClass $url */
        $url = DB::table('urls')->where('name', 'http://site1.com')->first();
        $this->url = $url;
    }
}
