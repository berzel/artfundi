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
        $listClients = Permission::create(['name' => 'list-clients']);

        $user->addRole($admin);
        $admin->givePermission($listClients);

        return $user;
    }

    public function test_guest_user_cannot_view_clients_list(): void
    {
        $response = $this->getJson('/api/clients');

        $response->assertUnauthorized();
    }

    public function test_user_without_permission_cannot_view_clients_list(): void
    {
        $response = $this->actingAs($this->user())->getJson('/api/clients');

        $response->assertForbidden();
    }

    public function test_admin_can_view_clients_list(): void
    {
        $clients = Client::factory()->count(5)->create();

        $response = $this->actingAs($this->adminUser())->getJson('/api/clients');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'first_name', 'last_name', 'email', 'phone'
                    ]
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
