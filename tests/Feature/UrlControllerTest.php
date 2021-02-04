<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Url;

class UrlControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Url::factory()->count(2)->make();
    }
    public function testIndex()
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }
}
