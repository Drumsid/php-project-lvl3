<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UrlChecksControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::table('urls')->insert([
            ['name' => "https://test.ru", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'exampleSite2.ru', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        DB::table('url_checks')->insert([
            'url_id' => 1,
            'status_code' => 200,
            'h1' => 'header',
            'keywords' => 'keywords',
            'description' => 'description',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    public function testStore(): void
    {
        $data = ['id' => 1, 'name' => 'https://test.ru'];
        $url = $data['name'];
        $expected = [
            'url_id'   => 1,
            'status_code' => 200,
            'keywords'    => 'keywords test fixture',
            'h1'          => 'Header test fixtures',
            'description' => 'description test fixture',
        ];
        $html = file_get_contents(__DIR__ . '/../fixtures/test.html') ?? null;
        Http::fake([$url => Http::response($html)]);
        $response = $this->post(route('urls.checks.store', $data['id']));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', $expected);
    }
}
