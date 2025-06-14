<?php

namespace Tests\Feature\Clients;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CreateClientTest extends TestCase
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

    protected function payload(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->e164PhoneNumber(),
        ];
    }

    public function test_an_admin_can_create_a_new_client(): void
    {
        $payload = $this->payload();

        $response = $this->actingAs($this->adminUser())
            ->postJson('/api/clients', $payload);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'email' => $payload['email'],
                'phone' => $payload['phone'],
            ]);

        $this->assertDatabaseHas('clients', $payload);
    }

    public function test_guest_cannot_create_client(): void
    {
        $payload = $this->payload();

        $response = $this->postJson('/api/clients', $payload);

        $response->assertUnauthorized();
    }

    public function test_non_admin_user_cannot_create_client(): void
    {
        $payload = $this->payload();

        $response = $this->actingAs($this->user())->postJson('/api/clients', $payload);

        $response->assertForbidden();
    }

    public function test_validation_error_when_email_is_missing(): void
    {
        $payload = Arr::except($this->payload(), ['email']);

        $response = $this->actingAs($this->adminUser())
            ->postJson('/api/clients', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('email');
    }

    public function test_validation_error_when_first_name_is_missing(): void
    {
        $payload = Arr::except($this->payload(), ['first_name']);

        $response = $this->actingAs($this->adminUser())
            ->postJson('/api/clients', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('first_name');
    }

    public function test_validation_error_when_phone_is_missing(): void
    {
        $payload = Arr::except($this->payload(), ['phone']);

        $response = $this->actingAs($this->adminUser())
            ->postJson('/api/clients', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('phone');
    }

    public function test_validation_error_when_last_name_is_missing(): void
    {
        $payload = Arr::except($this->payload(), ['last_name']);

        $response = $this->actingAs($this->adminUser())
            ->postJson('/api/clients', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('last_name');
    }
}
