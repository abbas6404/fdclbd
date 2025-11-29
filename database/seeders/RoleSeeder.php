<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles for Real Estate/Construction Company
        $roles = [
            'Super Admin',
            'Admin',
            'Manager',
            'Sales Manager',
            'Sales Agent',
            'Accountant',
            'Project Manager',
            'Contractor',
            'User'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $this->command->info('Roles seeded successfully!');
        $this->command->info('Total roles created: ' . count($roles));
    }
}
