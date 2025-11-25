<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'role' => 'ADMIN',
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'John Doe',
                'role' => 'CUSTOMER',
                'password' => Hash::make('user'),
                'email_verified_at' => now(),
            ]
        );

        $users = User::factory(11)->create();
        $users = collect([$admin, $user])->merge($users);

        Category::updateOrCreate([
            'id' => 1,
            'name' => 'Racunari',
            'parent_id' => null,
        ]);

        Category::updateOrCreate([
            'id' => 2,
            'name' => 'Komponente',
            'parent_id' => 1,
        ]);

        Category::updateOrCreate([
            'id' => 3,
            'name' => 'CPU',
            'parent_id' => 2,
        ]);

        Category::updateOrCreate([
            'id' => 4,
            'name' => 'GPU',
            'parent_id' => 2,
        ]);

        Category::updateOrCreate([
            'id' => 5,
            'name' => 'Laptopovi',
            'parent_id' => 1,
        ]);

        Category::factory()
            ->count(5)
            ->create();

        $users->each(function ($user) {
            $random = mt_rand(3,5);
            Ad::factory()->count(mt_rand(0,5))->create([
                'user_id' => $user->id,
                'category_id' => $random,
                'image' => $random . mt_rand(1,2) . '.png',
            ]);
        });

    }
}
