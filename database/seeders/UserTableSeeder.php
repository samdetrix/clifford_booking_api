<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1, 
        ]);

        User::create([
            'name' => 'William Sanke',
            'email' => 'agent1@example.com',
            'password' => bcrypt('password'),
            'role_id' => 2, 
        ]);

        User::create([
            'name' => 'Mary Waithera',
            'email' => 'agent2@example.com',
            'password' => bcrypt('password'),
            'role_id' => 2,
        ]);


        $this->command->info('Users table seeded!');
    }
}
