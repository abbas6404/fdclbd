<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default users with roles
        
        // Create a super admin user with ID 1
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'id' => 1,
                'name' => 'Super Administrator',
                'code' => 'SUPER-001',
                'phone' => '01742184298',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]
        );
        
        // Force the ID to be 1 if it wasn't set
        if ($superAdmin->id !== 1) {
            $superAdmin->forceFill(['id' => 1])->save();
        }
        
        $superAdmin->assignRole('Super Admin');
        
        // Create an admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Administrator',
                'code' => 'ADMIN-001',
                'phone' => '01752345678',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]
        );
        $admin->assignRole('Admin');

        // Create a Manager user
        $manager = User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name' => 'Manager Ahmed Hossain',
                'code' => 'MGR-001',
                'phone' => '01712345678',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]
        );
        $manager->assignRole('Manager');

        // Create a Sales Manager user
        $salesManager = User::firstOrCreate(
            ['email' => 'salesmanager@gmail.com'],
            [
                'name' => 'Sales Manager Fatima Begum',
                'code' => 'SM-001',
                'phone' => '01812345678',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]
        );
        $salesManager->assignRole('Sales Manager');

        // Create a Sales Agent user
        $salesAgent = User::firstOrCreate(
            ['email' => 'salesagent@gmail.com'],
            [
                'name' => 'Sales Agent Ahmed Hasan',
                'code' => 'SA-001',
                'phone' => '01912345678',   
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]
        );
        $salesAgent->assignRole('Sales Agent');
        
        // Create another Sales Agent user
        $salesAgent2 = User::firstOrCreate(
            ['email' => 'salesagent2@gmail.com'],
            [
                'name' => 'Sales Agent Makbul Hasan',
                'code' => 'SA-002',
                'phone' => '01912345675',   
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]
        );
        $salesAgent2->assignRole('Sales Agent');

        // Create an Accountant user
        $accountant = User::firstOrCreate(
            ['email' => 'accountant@gmail.com'],
            [
                'name' => 'Accountant Sarah Johnson',
                'code' => 'ACC-001',
                'phone' => '01612345678',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]
        );
        $accountant->assignRole('Accountant');

        // Create a Project Manager user
        $projectManager = User::firstOrCreate(
            ['email' => 'projectmanager@gmail.com'],
            [
                'name' => 'Project Manager Ayesha Begum',
                'code' => 'PM-001',
                'phone' => '01512345678',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]
        );
        $projectManager->assignRole('Project Manager');

        // Create a Contractor user
        $contractor = User::firstOrCreate(
            ['email' => 'contractor@gmail.com'],
            [
                'name' => 'Contractor Rahim',
                'code' => 'CON-001',
                'phone' => '01412345678',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]
        );
        $contractor->assignRole('Contractor');

        // Create a regular User
        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Regular User',
                'code' => 'USER-001',
                'phone' => '01212345678',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678')
            ]
        );
        $user->assignRole('User');
        
        $this->command->info('Users seeded successfully!');
        $this->command->info('Super Admin ID: ' . $superAdmin->id);
        $this->command->info('Login Credentials:');
        $this->command->info('Super Admin: superadmin@gmail.com / 12345678');
        $this->command->info('Admin: admin@gmail.com / 12345678');
        $this->command->info('Manager: manager@gmail.com / 12345678');
        $this->command->info('Sales Manager: salesmanager@gmail.com / 12345678');
        $this->command->info('Sales Agent: salesagent@gmail.com / 12345678');
        $this->command->info('Sales Agent 2: salesagent2@gmail.com / 12345678');
        $this->command->info('Accountant: accountant@gmail.com / 12345678');
        $this->command->info('Project Manager: projectmanager@gmail.com / 12345678');
        $this->command->info('Contractor: contractor@gmail.com / 12345678');
        $this->command->info('User: user@gmail.com / 12345678');
    }
}
