<?php

namespace Tests\Feature\Clients;

use App\Models\Client;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListClientsTest extends TestCase
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
        $updateClients = Permission::create(['name' => 'update-clients']);

        $user->addRole($admin);
        $admin->givePermission($updateClients);

        return $user;
    }

    public function test_admin_can_view_clients_list(): void
    {
        $clients = Client::factory()->count(5)->create();

        $response = $this->actingAs($this->adminUser())->getJson('/api/clients');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'first_name', 'last_name', 'email', 'phone']
                ],
                'links',
                'meta',
            ]);

        $this->assertEquals(
            $clients->sortByDesc('created_at')->pluck('id')->values()->toArray(),
            collect($response->json('data'))->pluck('id')->toArray()
        );
    }
}
