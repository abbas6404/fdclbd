<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Ahmed Rahman',
                'father_or_husband_name' => 'Abdul Rahman',
                'phone' => '01712345678',
                'address' => 'House 15, Road 7, Dhanmondi, Dhaka-1205',
                'email' => 'ahmed.rahman@email.com',
                'nid_or_passport_number' => '1234567890123',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Fatima Begum',
                'father_or_husband_name' => 'Mohammad Ali',
                'phone' => '01812345679',
                'address' => 'Flat 5A, Building 20, Gulshan 1, Dhaka-1212',
                'email' => 'fatima.begum@email.com',
                'nid_or_passport_number' => '2345678901234',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Karim Uddin',
                'father_or_husband_name' => 'Uddin Ahmed',
                'phone' => '01912345680',
                'address' => 'Block C, Mirpur 10, Dhaka-1216',
                'email' => 'karim.uddin@email.com',
                'nid_or_passport_number' => '3456789012345',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Nusrat Jahan',
                'father_or_husband_name' => 'Jahan Ahmed',
                'phone' => '01512345681',
                'address' => 'House 25, Uttara Sector 3, Dhaka-1230',
                'email' => 'nusrat.jahan@email.com',
                'nid_or_passport_number' => '4567890123456',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Rashid Hassan',
                'father_or_husband_name' => 'Hassan Ali',
                'phone' => '01612345682',
                'address' => 'Apartment 3B, Cox\'s Bazar, Chittagong',
                'email' => 'rashid.hassan@email.com',
                'nid_or_passport_number' => '5678901234567',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Salma Khatun',
                'father_or_husband_name' => 'Khatun Mia',
                'phone' => '01712345683',
                'address' => 'Flat 8, Building 12, Old Dhaka, Dhaka-1100',
                'email' => 'salma.khatun@email.com',
                'nid_or_passport_number' => '6789012345678',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Tariq Islam',
                'father_or_husband_name' => 'Islam Uddin',
                'phone' => '01812345684',
                'address' => 'House 30, Road 11, Banani, Dhaka-1213',
                'email' => 'tariq.islam@email.com',
                'nid_or_passport_number' => '7890123456789',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Rokeya Sultana',
                'father_or_husband_name' => 'Sultana Begum',
                'phone' => '01912345685',
                'address' => 'Block D, Mirpur 12, Dhaka-1216',
                'email' => 'rokeya.sultana@email.com',
                'nid_or_passport_number' => '8901234567890',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Mahbub Alam',
                'father_or_husband_name' => 'Alam Khan',
                'phone' => '01512345686',
                'address' => 'Flat 2C, Building 5, Dhanmondi 27, Dhaka-1205',
                'email' => 'mahbub.alam@email.com',
                'nid_or_passport_number' => '9012345678901',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Nasrin Akter',
                'father_or_husband_name' => 'Akter Hossain',
                'phone' => '01612345687',
                'address' => 'House 18, Road 3, Gulshan 2, Dhaka-1212',
                'email' => 'nasrin.akter@email.com',
                'nid_or_passport_number' => '0123456789012',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Sajjad Hossain',
                'father_or_husband_name' => 'Hossain Mia',
                'phone' => '01712345688',
                'address' => 'Apartment 4A, Building 8, Uttara Sector 7, Dhaka-1230',
                'email' => 'sajjad.hossain@email.com',
                'nid_or_passport_number' => '1234567890124',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Rashida Begum',
                'father_or_husband_name' => 'Begum Ali',
                'phone' => '01812345689',
                'address' => 'Flat 6B, Building 15, Mohammadpur, Dhaka-1207',
                'email' => 'rashida.begum@email.com',
                'nid_or_passport_number' => '2345678901235',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Kamal Uddin',
                'father_or_husband_name' => 'Uddin Ahmed',
                'phone' => '01912345690',
                'address' => 'House 22, Road 5, Baridhara, Dhaka-1212',
                'email' => 'kamal.uddin@email.com',
                'nid_or_passport_number' => '3456789012346',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Shahida Parvin',
                'father_or_husband_name' => 'Parvin Khan',
                'phone' => '01512345691',
                'address' => 'Block E, Mirpur 6, Dhaka-1216',
                'email' => 'shahida.parvin@email.com',
                'nid_or_passport_number' => '4567890123457',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Abdul Malek',
                'father_or_husband_name' => 'Malek Hossain',
                'phone' => '01612345692',
                'address' => 'Flat 3D, Building 10, Tejgaon, Dhaka-1208',
                'email' => 'abdul.malek@email.com',
                'nid_or_passport_number' => '5678901234568',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        $this->command->info('Customers seeded successfully!');
        $this->command->info('Total customers created: ' . count($customers));
    }
}
