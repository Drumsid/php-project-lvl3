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
    public function testStore(): void
    {
        $data = [
            'id' => 1,
            'name' => 'https://test.ru',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        $id = DB::table('urls')->insertGetId($data);
        $url = $data['name'];
        $expected = [
            'url_id'   => $id,
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
