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
            ['name' => "https://yandex.ru", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'exampleSite2.ru', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        // DB::table('url_checks')->insert([
        //     'url_id' => 1,
        //     'status_code' => 200,
        //     'h1' => 'header',
        //     'keywords' => 'keywords',
        //     'description' => 'description',
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);
    }
    public function testIndex()
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }
    public function testShow()
    {
        $response = $this->get(route('urls.show', 1));
        $response->assertOk();
    }
    public function testStore()
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
    public function testChecks()
    {
        $data = ['id' => 1, 'name' => 'https://yandex.ru'];
        $response = $this->post(route('urls.checks', $data['id']));
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $data);

        // Http::fake();
        // $fakeResponse = Http::get($data['name']);
        // $this->assertTrue($fakeResponse->ok());

        // DB::table('url_checks')->insert([
        //     'url_id' => $data['id'],
        //     'status_code' => 200,
        //     'h1' => 'header_test',
        //     'keywords' => 'keywords',
        //     'description' => 'description',
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);
        // $this->assertDatabaseHas('url_checks', ['h1' => 'header_test']);
        $url = 'test.ru';
        $html = file_get_contents(__DIR__ . '/../fixtures/test.html');
        // dd($html);
        $fake = Http::fake([$url => Http::response($html, 200)]);
        // dd($fake);
        $document = new Document($html);
        $h1 = optional($document->first('h1'))->text();
        $keywords = optional($document->first('meta[name=keywords]'))->getAttribute('content');
        $description = optional($document->first('meta[name=description]'))->getAttribute('content');
    }
}
