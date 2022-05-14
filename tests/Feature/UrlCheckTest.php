<?php

declare(strict_types=1);

namespace Tests\Feature;

use Carbon\CarbonImmutable;
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

        $dateTime = new CarbonImmutable('2022-01-01 12:00:00');
        CarbonImmutable::setTestNow($dateTime);

        $data = ['url' => $this->url->id];
        $storeResponse = $this->post(route('urls.checks.store', $data));

        $storeResponse->assertRedirect();
        $storeResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('url_checks', [
            'url_id'      => $this->url->id,
            'status_code' => 200,
            'title'       => 'Test article',
            'description' => 'Article description',
            'h1'          => 'Title for the article',
            'created_at'  => '2022-01-01 12:00:00',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UrlSeeder::class);

        $this->url = DB::table('urls')->where('name', 'http://site2.com')->get()->firstOrFail();
    }
}
