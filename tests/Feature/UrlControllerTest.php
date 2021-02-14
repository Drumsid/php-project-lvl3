<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

class UrlControllerTest extends TestCase
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
    public function testIndex(): void
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }
    public function testShow(): void
    {
        $response = $this->get(route('urls.show', 1));
        $response->assertOk();
    }
    public function testStore(): void
    {
        $data = ['name' => "https://test.ru"];
        $response = $this->post(route('urls.store'), ['url' => $data]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $data);

        $data = ['name' => ""];
        $response = $this->post(route('urls.store'), ['url' => $data]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('urls', $data);
    }
    public function testChecks(): void
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
        $response = $this->post(route('urls.checks', $data['id']));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', $expected);
    }
}
