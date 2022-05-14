<?php

declare(strict_types=1);

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

    public function testStore(): void
    {
        $urlData = ['name' => 'http://site4.com'];
        $storeResponse = $this->post(route('urls.store'), ['url' => $urlData]);

        $storeResponse->assertRedirect();
        $storeResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('urls', $urlData);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UrlSeeder::class);

        $this->url = DB::table('urls')->where('name', 'http://site1.com')->get()->firstOrFail();
    }
}
