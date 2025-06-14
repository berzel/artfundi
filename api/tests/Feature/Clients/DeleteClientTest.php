<?php

namespace Tests\Feature\Clients;

use App\Models\Client;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteClientTest extends TestCase
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
        $deleteClients = Permission::create(['name' => 'delete-clients']);

        $user->addRole($admin);
        $admin->givePermission($deleteClients);

        return $user;
    }

    public function test_admin_can_delete_a_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->adminUser())->deleteJson("/api/clients/$client->id");

        $response->assertStatus(204);

        $this->assertSoftDeleted('clients', [
            'id' => $client->id,
        ]);
    }

    public function test_guest_cannot_delete_a_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->deleteJson("/api/clients/$client->id");

        $response->assertUnauthorized();
        $this->assertDatabaseHas('clients', ['id' => $client->id]);
    }

    public function test_non_admin_user_cannot_delete_a_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user())->deleteJson("/api/clients/$client->id");

        $response->assertForbidden();
        $this->assertDatabaseHas('clients', ['id' => $client->id]);
    }
}
