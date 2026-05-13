<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            'dashboard.view',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Brands
            'brands.view',
            'brands.create',
            'brands.edit',
            'brands.delete',

            // Categories
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            // Products
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            // Units
            'units.view',
            'units.create',
            'units.edit',
            'units.delete',

            // Orders
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',

            // Students
            'students.view',
            'students.create',
            'students.edit',
            'students.delete',

            // Top Up
            'topups.view',
            'topups.create',
            'topups.edit',
            'topups.delete',

            // Wallet Transactions
            'wallet-transactions.view',

            // Settings
            'settings.view',
            'settings.edit',

            // POS Cashier
            'pos-cashier.view',

            // POS Cashier WA
            'pos-cashier-wa.view',

            // Sales Report
            'sales-report.view',

            // Receipt Settings
            'receipt-settings.view',
            'receipt-settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        // Super Admin — akses semua
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin — semua kecuali role management
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(
            Permission::whereNotIn('name', ['roles.create', 'roles.edit', 'roles.delete'])->get()
        );

        // Kasir — hanya POS & orders view
        $kasir = Role::firstOrCreate(['name' => 'kasir']);
        $kasir->syncPermissions([
            'dashboard.view',
            'pos.view',
            'pos.create',
            'orders.view',
            'orders.create',
            'products.view',
        ]);

        // Siswa — hanya lihat transaksi wallet sendiri
        $siswa = Role::firstOrCreate(['name' => 'siswa']);
        $siswa->syncPermissions([
            'dashboard.view',
            'wallet.view',
            'transactions.view',
        ]);

        $this->command->info('✅ Roles & Permissions berhasil dibuat!');
    }
}