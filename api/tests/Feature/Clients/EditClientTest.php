<?php

namespace Tests\Feature\Clients;

use App\Models\Client;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class EditClientTest extends TestCase
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

    protected function payload(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->e164PhoneNumber(),
        ];
    }

    public function test_admin_can_update_a_client_record(): void
    {
        $client = Client::factory()->create();
        $payload = $this->payload();

        $response = $this->actingAs($this->adminUser())
            ->putJson("/api/clients/$client->id", $payload);

        $response->assertOk()->assertJsonFragment($payload);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            ...$payload,
        ]);
    }

    public function test_guest_user_cannot_update_a_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->putJson("/api/clients/$client->id", $this->payload());

        $response->assertUnauthorized();
    }

    public function test_non_admin_user_cannot_update_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user())->putJson("/api/clients/$client->id",  $this->payload());

        $response->assertForbidden();
    }

    public function test_all_fields_are_required_when_updating_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->adminUser())->putJson("/api/clients/$client->id", []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'email',
                'phone',
            ]);
    }

    public function test_email_must_be_unique_except_when_updating_same_client(): void
    {
        Client::factory()
            ->count(2)
            ->create();

        $clients = Client::all();
        $client2 = $clients->last();
        $client1 = $clients->first();

        $response2 = $this->actingAs($this->adminUser())->putJson("/api/clients/$client1->id", [
            ...$this->payload(),
            'email' => $client2->email,
        ]);

        $response2->assertStatus(422)->assertJsonValidationErrors('email');
    }
}
