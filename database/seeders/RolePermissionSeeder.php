<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Tạo Permissions
        $permissions = [
            // User Management
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            
            // Post Management
            'post-list',
            'post-create',
            'post-edit',
            'post-delete',
            'post-publish',
            
            // Category Management
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            
            // Tag Management
            'tag-list',
            'tag-create',
            'tag-edit',
            'tag-delete',
            
            // Comment Management
            'comment-list',
            'comment-approve',
            'comment-delete',
            
            // Media Management
            'media-list',
            'media-upload',
            'media-delete',
            
            // Menu Management
            'menu-list',
            'menu-create',
            'menu-edit',
            'menu-delete',
            
            // Settings
            'settings-edit',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Tạo Roles và gán Permissions

        // 1. Admin - Có tất cả quyền
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // 2. Editor - Quản lý nội dung
        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo([
            'post-list',
            'post-create',
            'post-edit',
            'post-delete',
            'post-publish',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'tag-list',
            'tag-create',
            'tag-edit',
            'tag-delete',
            'comment-list',
            'comment-approve',
            'comment-delete',
            'media-list',
            'media-upload',
            'media-delete',
        ]);

        // 3. Author - Viết và quản lý bài viết của mình
        $authorRole = Role::create(['name' => 'author']);
        $authorRole->givePermissionTo([
            'post-list',
            'post-create',
            'post-edit',
            'category-list',
            'tag-list',
            'tag-create',
            'media-list',
            'media-upload',
        ]);

        // 4. Subscriber - Chỉ xem và comment
        $subscriberRole = Role::create(['name' => 'subscriber']);
        // Subscriber không có permissions đặc biệt
    }
}
