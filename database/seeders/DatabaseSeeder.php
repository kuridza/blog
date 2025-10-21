<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
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

        $moderator = User::updateOrCreate(
            ['email' => 'moderator@example.com'],
            [
                'name' => 'Moderator',
                'role' => 'MOD',
                'password' => Hash::make('mod'),
                'email_verified_at' => now(),
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'John Doe',
                'role' => 'USER',
                'password' => Hash::make('user'),
                'email_verified_at' => now(),
            ]
        );

        $users = User::factory(10)->create();

        $allUsers = collect([$admin, $moderator, $user])->merge($users);

        Post::factory(100)->create()->each(function (Post $post) use ($allUsers) {
            $post->user()->associate($allUsers->random())->save();

            Comment::factory(mt_rand(0, 33))->create([
                'post_id' => $post->id,
                'user_id' => $allUsers->random()->id,
            ]);
        });
    }
}
