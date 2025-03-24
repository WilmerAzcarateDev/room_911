<?php

namespace Database\Seeders;

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
            'document'=>'1234567890',
            'name' => 'User',
            'last_name'=>'Test',
            'email' => 'test@example.com',
            'password' => 'admin123',
        ]);

        $this->call(RoleSeeder::class);

        $user->assignRole('admin_room_911');

        $this->call(ProductionDepartamentSeeder::class);

    }
}
