<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'username' => 'admin',
            'status' => 1,
        ]);
        $admin->assignRole('admin');
        $admin->update(['role' => 'admin']);

        // Tạo Editor User
        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('password'),
            'username' => 'editor',
            'status' => 1,
        ]);
        $editor->assignRole('editor');
        $editor->update('editor');

        // Tạo Author User
        $author = User::create([
            'name' => 'Author User',
            'email' => 'author@example.com',
            'password' => Hash::make('password'),
            'username' => 'author',
            'status' => 1,
        ]);
        $author->assignRole('author');
        $author->update('author');

        // Tạo Subscriber User
        $subscriber = User::create([
            'name' => 'Subscriber User',
            'email' => 'subscriber@example.com',
            'password' => Hash::make('password'),
            'username' => 'subscriber',
            'status' => 1,
        ]);
        $subscriber->assignRole('subscriber');
        $subscriber->update('subscriber');
    }
}
