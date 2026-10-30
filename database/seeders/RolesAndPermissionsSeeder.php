<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Users
            'users.view', 'users.create', 'users.update', 'users.delete',
            // Branches
            'branches.view', 'branches.create', 'branches.update', 'branches.delete',
            // Catalog
            'minerals.view', 'minerals.create', 'minerals.update', 'minerals.delete',
            'categories.view', 'categories.create', 'categories.update', 'categories.delete',
            // People
            'customers.view', 'customers.create', 'customers.update', 'customers.delete',
            'suppliers.view', 'suppliers.create', 'suppliers.update', 'suppliers.delete',
            // Transactions
            'sales.view', 'sales.create', 'sales.refund',
            'purchases.view', 'purchases.create', 'purchases.receive', 'purchases.delete',
            // Stock
            'stock.view', 'stock.adjust',
            // Reports
            'reports.view',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // Super admin gets everything
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin — full control except cross-branch (still gets all perms,
        // but policies restrict branches via branch_id)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // Manager — branch-scoped everything except user/branch management
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'minerals.view', 'minerals.create', 'minerals.update', 'minerals.delete',
            'categories.view', 'categories.create', 'categories.update',
            'customers.view', 'customers.create', 'customers.update',
            'suppliers.view', 'suppliers.create', 'suppliers.update',
            'sales.view', 'sales.create', 'sales.refund',
            'purchases.view', 'purchases.create', 'purchases.receive',
            'stock.view', 'stock.adjust',
            'reports.view',
        ]);

        // Cashier — POS + view customers
        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $cashier->syncPermissions([
            'minerals.view',
            'customers.view', 'customers.create',
            'sales.view', 'sales.create',
            'stock.view',
        ]);
    }
}