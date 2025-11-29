<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing employees
        Employee::query()->delete();

        $employees = [
            [
                'name' => 'Mohammad Hasan',
                'phone' => '01711111111',
                'email' => 'hasan@company.com',
                'position' => 'Manager',
                'department' => 'Administration',
                'address' => 'House 10, Road 5, Dhanmondi, Dhaka-1205',
            ],
            [
                'name' => 'Fatima Khatun',
                'phone' => '01722222222',
                'email' => 'fatima@company.com',
                'position' => 'Accountant',
                'department' => 'Finance',
                'address' => 'Flat 5A, Building 20, Gulshan 1, Dhaka-1212',
            ],
            [
                'name' => 'Karim Uddin',
                'phone' => '01733333333',
                'email' => 'karim@company.com',
                'position' => 'Purchase Officer',
                'department' => 'Procurement',
                'address' => 'Block C, Mirpur 10, Dhaka-1216',
            ],
            [
                'name' => 'Nusrat Jahan',
                'phone' => '01744444444',
                'email' => 'nusrat@company.com',
                'position' => 'Store Keeper',
                'department' => 'Inventory',
                'address' => 'House 25, Uttara Sector 3, Dhaka-1230',
            ],
            [
                'name' => 'Rashid Hassan',
                'phone' => '01755555555',
                'email' => 'rashid@company.com',
                'position' => 'Assistant Manager',
                'department' => 'Operations',
                'address' => 'Apartment 3B, Building 15, Banani, Dhaka-1213',
            ],
            [
                'name' => 'Salma Begum',
                'phone' => '01766666666',
                'email' => 'salma@company.com',
                'position' => 'HR Officer',
                'department' => 'Human Resources',
                'address' => 'Flat 8, Building 12, Old Dhaka, Dhaka-1100',
            ],
            [
                'name' => 'Tariq Islam',
                'phone' => '01777777777',
                'email' => 'tariq@company.com',
                'position' => 'IT Support',
                'department' => 'IT',
                'address' => 'House 30, Road 11, Banani, Dhaka-1213',
            ],
            [
                'name' => 'Rokeya Sultana',
                'phone' => '01788888888',
                'email' => 'rokeya@company.com',
                'position' => 'Receptionist',
                'department' => 'Administration',
                'address' => 'Block D, Mirpur 12, Dhaka-1216',
            ],
            [
                'name' => 'Mahbub Alam',
                'phone' => '01799999999',
                'email' => 'mahbub@company.com',
                'position' => 'Security Officer',
                'department' => 'Security',
                'address' => 'Flat 2C, Building 5, Dhanmondi 27, Dhaka-1205',
            ],
            [
                'name' => 'Nasrin Akter',
                'phone' => '01700000000',
                'email' => 'nasrin@company.com',
                'position' => 'Office Assistant',
                'department' => 'Administration',
                'address' => 'House 18, Road 3, Gulshan 2, Dhaka-1212',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }

        $this->command->info('Employees seeded successfully!');
        $this->command->info('Total employees created: ' . count($employees));
    }
}
