<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
// use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UrlControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::table('urls')->insert([
            ['name' => 'exampleSite1.com', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'exampleSite2.ru', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'exampleSite3.com', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
    }
    public function testIndex()
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }
}
