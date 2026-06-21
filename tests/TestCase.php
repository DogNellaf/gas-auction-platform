<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed legal forms so factories work
        \Illuminate\Support\Facades\DB::table('legal_forms')->insertOrIgnore([
            ['id' => 1, 'title' => 'ООО', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'title' => 'ЗАО', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'title' => 'ОАО', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'title' => 'ИП',  'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
