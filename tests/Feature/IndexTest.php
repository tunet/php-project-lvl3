<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class IndexTest extends TestCase
{
    public function testShow(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
