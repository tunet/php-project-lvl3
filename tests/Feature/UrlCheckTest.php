<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Url;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UrlCheckTest extends TestCase
{
    public function testStore(): void
    {
        $html = file_get_contents(__DIR__ . '/../fixtures/index.html');
        Http::fake([
            '*' => Http::response($html, 200, ['Headers']),
        ]);

        /** @var \App\Models\Url $model */
        $model = Url::factory()->create(['name' => 'http://site2.com']);

        $dateTime = new CarbonImmutable('2022-01-01 12:00:00');
        CarbonImmutable::setTestNow($dateTime);

        $data = ['url' => $model->id];
        $storeResponse = $this->post(route('urls.checks.store', $data));

        $storeResponse->assertRedirect();
        $storeResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('url_checks', [
            'url_id'      => $model->id,
            'status_code' => 200,
            'title'       => 'Test article',
            'description' => 'Article description',
            'h1'          => 'Title for the article',
            'created_at'  => '2022-01-01 12:00:00',
        ]);
    }
}
