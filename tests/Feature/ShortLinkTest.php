<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ShortLink;
use Illuminate\Support\Facades\Auth;

class ShortLinkTest extends TestCase
{

    /**
     * A basic feature test example.
     */

     use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function creates_a_short_link_with_custom_code()
    {
        $response = $this->postJson('/api/shorten', [
            'original_url' => 'https://example.com',
            'custom_code' => 'test123'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('short_links', [
            'code' => 'test123',
            'original_url' => 'https://example.com'
        ]);
    }

    public function redirects_using_short_code()
    {
        $link = ShortLink::factory()->create();

        $response = $this->get("/{$link->code}");

        $response->assertRedirect($link->original_url);
        $this->assertEquals(1, $link->fresh()->clicks);
    }
}   