<?php

namespace Tests\Feature\Clients;

use App\Models\Client;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowClientTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function user(): User
    {
        return User::factory()->create();
    }

    protected function adminUser(): User
    {
        $user = $this->user();
        $admin = Role::create(['name' => 'admin']);
        $createClient = Permission::create(['name' => 'create-clients']);

        $user->addRole($admin);
        $admin->givePermission($createClient);

        return $user;
    }

    public function test_admin_can_view_a_single_client()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->adminUser())->getJson("/api/clients/$client->id");

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $client->id,
                    'first_name' => $client->first_name,
                    'last_name' => $client->last_name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                ]
            ]);
    }
}
