<?php

namespace Database\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

class UrlFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'       => "http://{$this->faker->unique()->domainName()}",
            'created_at' => CarbonImmutable::now(),
        ];
    }
}
