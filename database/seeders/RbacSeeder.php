<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('permissions')) {
            return;
        }

        $roles = [
            'admin' => 'Full system access',
            'editor' => 'Content management access',
            'seller' => 'Seller and catalog operations access',
            'user' => 'Regular customer access',
        ];

        foreach ($roles as $name => $description) {
            Role::query()->firstOrCreate(
                ['name' => $name],
                ['description' => $description],
            );
        }

        $permissions = [
            'access_admin_panel' => 'Can access the admin dashboard',
            'manage_users' => 'Can manage users',
            'manage_roles' => 'Can manage roles',
            'manage_permissions' => 'Can manage permissions',
            'manage_orders' => 'Can manage regular orders',
            'fulfill_orders' => 'Can fulfill regular orders',
            'manage_pre_orders' => 'Can manage pre-orders',
            'fulfill_pre_orders' => 'Can fulfill pre-orders',
            'manage_payments' => 'Can manage payments',
            'manage_products' => 'Can manage products',
            'sync_product_sheet' => 'Can sync product Google Sheets',
            'manage_product_features' => 'Can manage product features',
            'manage_categories' => 'Can manage categories',
            'manage_subcategories' => 'Can manage subcategories',
            'manage_offers' => 'Can manage offers',
            'manage_posts' => 'Can manage posts',
            'create_post' => 'Can create posts',
            'edit_post' => 'Can edit posts',
            'delete_post' => 'Can delete posts',
            'manage_banners' => 'Can manage banners',
            'manage_faqs' => 'Can manage FAQs',
            'manage_guidelines' => 'Can manage guidelines',
            'manage_about_pages' => 'Can manage about page content',
            'manage_terms_conditions' => 'Can manage terms and conditions',
            'manage_privacy_policies' => 'Can manage privacy policies',
            'manage_settings' => 'Can manage site settings',
            'manage_seo_settings' => 'Can manage SEO settings',
        ];

        foreach ($permissions as $name => $description) {
            Permission::query()->firstOrCreate(
                ['name' => $name],
                ['description' => $description],
            );
        }

        $admin = Role::query()->where('name', 'admin')->first();
        $editor = Role::query()->where('name', 'editor')->first();
        $seller = Role::query()->where('name', 'seller')->first();
        $user = Role::query()->where('name', 'user')->first();

        $admin?->syncPermissions(array_keys($permissions));

        $editor?->syncPermissions([
            'access_admin_panel',
            'manage_posts',
            'create_post',
            'edit_post',
            'delete_post',
            'manage_banners',
            'manage_faqs',
            'manage_guidelines',
            'manage_about_pages',
            'manage_terms_conditions',
            'manage_privacy_policies',
            'manage_settings',
            'manage_seo_settings',
        ]);

        $seller?->syncPermissions([
            'access_admin_panel',
            'manage_orders',
            'fulfill_orders',
            'manage_pre_orders',
            'fulfill_pre_orders',
            'manage_products',
            'sync_product_sheet',
            'manage_product_features',
            'manage_categories',
            'manage_subcategories',
            'manage_offers',
        ]);

        $user?->syncPermissions([]);

        User::query()->each(function (User $userModel): void {
            $roleName = match ((int) $userModel->role_id) {
                1 => 'admin',
                3 => 'seller',
                default => 'user',
            };

            $userModel->assignRole($roleName);
        });
    }
}
