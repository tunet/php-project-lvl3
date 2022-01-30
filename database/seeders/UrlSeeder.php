<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UrlSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 3) as $value) {
            DB::table('urls')->insert([
                'name'       => "http://site{$value}.com",
                'created_at' => CarbonImmutable::now(),
            ]);
        }
    }
}
