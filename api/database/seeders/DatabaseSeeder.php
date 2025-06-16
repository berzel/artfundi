<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
        ]);

        $admin = Role::create(['name' => 'admin']);
        $createClient = Permission::create(['name' => 'create-clients']);

        $user->addRole($admin);
        $admin->givePermission($createClient);

        Client::factory()->count(20)->create();
    }
}
