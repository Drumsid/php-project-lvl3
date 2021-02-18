<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UrlControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::table('urls')->insert([
            ['name' => "https://test.ru", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
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
}
