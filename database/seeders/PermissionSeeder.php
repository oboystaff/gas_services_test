<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'users.view'],
            ['name' => 'users.create'],
            ['name' => 'users.update'],

            ['name' => 'roles.view'],
            ['name' => 'roles.create'],
            ['name' => 'roles.update'],

            ['name' => 'permissions.view'],
            ['name' => 'permissions.create'],
            ['name' => 'permissions.update'],

            ['name' => 'branches.view'],
            ['name' => 'branches.create'],
            ['name' => 'branches.update'],

            ['name' => 'communities.view'],
            ['name' => 'communities.create'],
            ['name' => 'communities.update'],

            ['name' => 'rates.view'],
            ['name' => 'rates.create'],
            ['name' => 'rates.update'],

            ['name' => 'customers.view'],
            ['name' => 'customers.create'],
            ['name' => 'customers.update'],

            ['name' => 'drivers.view'],
            ['name' => 'drivers.create'],
            ['name' => 'drivers.update'],

            ['name' => 'vehicles.view'],
            ['name' => 'vehicles.create'],
            ['name' => 'vehicles.update'],

            ['name' => 'gas-requests.view'],
            ['name' => 'gas-requests.create'],
            ['name' => 'gas-requests.update'],
            ['name' => 'gas-requests.approve'],
            ['name' => 'gas-requests.reverse'],

            ['name' => 'invoices.view'],
            ['name' => 'invoices.create'],
            ['name' => 'invoices.update'],

            ['name' => 'payments.view'],
            ['name' => 'payments.create'],
            ['name' => 'payments.update'],

            ['name' => 'reports.view'],

            ['name' => 'dashboards.operational'],
            ['name' => 'dashboards.financial'],
        ];

        $time_stamp = Carbon::now()->toDateTimeString();

        foreach ($data as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['created_at' => $time_stamp, 'updated_at' => $time_stamp]
            );
        }
    }
}
