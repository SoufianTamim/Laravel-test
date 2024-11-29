<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Clear existing users (optional)
        User::truncate();

        // Create 45 users
        for ($i = 0; $i < 45; $i++) {
            try {
                // Generate a random unique email address
                $email = 'user' . Str::random(5) . '@example.com';

                User::factory()->create([
                    'email' => $email,
                ]);
                echo "Created user " . ($i + 1) . " of 45\n";
            } catch (\Exception $e) {
                echo "Error creating user " . ($i + 1) . ": " . $e->getMessage() . "\n";
            }
        }
    }
}
