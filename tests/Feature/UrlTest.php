<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Url;
use Database\Seeders\UrlSeeder;
use Tests\TestCase;

class UrlTest extends TestCase
{
    private Url $url;

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

        $this->url = Url::factory()->create(['name' => 'http://site1.com']);
    }
}
