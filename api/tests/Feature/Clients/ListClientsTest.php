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

    public function test_it_filters_users_by_first_name()
    {
        Client::factory()->create(['first_name' => 'John']);
        Client::factory()->create(['first_name' => 'Jane']);
        Client::factory()->create(['first_name' => 'Jack']);

        $response = $this->actingAs($this->adminUser())->getJson('/api/clients?first_name=John');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['first_name' => 'John'])
            ->assertJsonMissing(['first_name' => 'Jane'])
            ->assertJsonMissing(['first_name' => 'Jack']);
    }

    public function test_it_filters_users_by_last_name()
    {
        Client::factory()->create(['last_name' => 'Doe']);
        Client::factory()->create(['last_name' => 'Smith']);
        Client::factory()->create(['last_name' => 'Johnson']);

        $response = $this->actingAs($this->adminUser())->getJson('/api/clients?last_name=Doe');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['last_name' => 'Doe'])
            ->assertJsonMissing(['last_name' => 'Smith'])
            ->assertJsonMissing(['last_name' => 'Johnson']);
    }

    public function test_it_filters_users_by_email()
    {
        Client::factory()->create(['email' => 'john@example.com']);
        Client::factory()->create(['email' => 'jane@example.com']);
        Client::factory()->create(['email' => 'jack@example.com']);

        $response = $this->actingAs($this->adminUser())->getJson('/api/clients?email=john@example.com');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['email' => 'john@example.com'])
            ->assertJsonMissing(['email' => 'jane@example.com'])
            ->assertJsonMissing(['email' => 'jack@example.com']);
    }

    public function test_it_filters_users_by_phone()
    {
        Client::factory()->create(['phone' => '+15551234567']);
        Client::factory()->create(['phone' => '+15559876543']);
        Client::factory()->create(['phone' => '+15557654321']);

        $response = $this->actingAs($this->adminUser())->getJson('/api/clients?phone=%2B15551234567');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['phone' => '+15551234567'])
            ->assertJsonMissing(['phone' => '+15559876543'])
            ->assertJsonMissing(['phone' => '+15557654321']);
    }
}
