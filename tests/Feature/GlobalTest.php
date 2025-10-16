<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use App\Models\User; 

use App\Models\Module; 

use App\Models\UserModule;


use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Laravel\Sanctum\Sanctum;



use Tests\TestCase;

class GlobalTest extends TestCase
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

        public function test_api_test_endpoint_returns_json()
    {
        $response = $this->getJson('/api/test');
        $response->assertStatus(200)->assertJson(['message' => 'API fonctionne !']);
    }






    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }


    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['user_id', 'token']);
    }



    

public function test_user_can_activate_module()
{
    $user = User::factory()->create();
    $module = Module::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/modules/{$module->id}/activate");

    $response->assertStatus(200);

    $this->assertDatabaseHas('user_modules', [
        'user_id' => $user->id,
        'module_id' => $module->id,
        'active' => true,
    ]);
}   

public function test_activate_non_existing_module_returns_404()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/modules/999/activate');

    $response->assertStatus(404);
}   


    public function test_unauthenticated_user_cannot_access_protected_route()
    {
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);
    }
    

    public function test_user_can_deactivate_module()
{
    $user = User::factory()->create();
    $module = Module::factory()->create();

    $user->modules()->attach($module, ['active' => true]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/modules/{$module->id}/deactivate");

    $response->assertStatus(200);

    $this->assertDatabaseHas('user_modules', [
        'user_id' => $user->id,
        'module_id' => $module->id,
        'active' => false,
    ]);
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
