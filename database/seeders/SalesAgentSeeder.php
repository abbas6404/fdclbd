<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesAgent;

class SalesAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salesAgents = [
            [
                'name' => 'Mohammad Ali',
                'phone' => '01712345601',
                'address' => 'House 10, Road 5, Dhanmondi, Dhaka-1205',
                'nid_or_passport_number' => '1111111111111',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Rashida Begum',
                'phone' => '01812345602',
                'address' => 'Flat 3A, Building 8, Gulshan 1, Dhaka-1212',
                'nid_or_passport_number' => '2222222222222',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Karim Uddin',
                'phone' => '01912345603',
                'address' => 'Block B, Mirpur 10, Dhaka-1216',
                'nid_or_passport_number' => '3333333333333',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Fatima Khatun',
                'phone' => '01512345604',
                'address' => 'House 25, Uttara Sector 3, Dhaka-1230',
                'nid_or_passport_number' => '4444444444444',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Abdul Rahman',
                'phone' => '01612345605',
                'address' => 'Apartment 2B, Cox\'s Bazar, Chittagong',
                'nid_or_passport_number' => '5555555555555',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Nasrin Akter',
                'phone' => '01712345606',
                'address' => 'Flat 5C, Building 12, Old Dhaka, Dhaka-1100',
                'nid_or_passport_number' => '6666666666666',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Sajjad Hossain',
                'phone' => '01812345607',
                'address' => 'House 30, Road 11, Banani, Dhaka-1213',
                'nid_or_passport_number' => '7777777777777',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Rokeya Sultana',
                'phone' => '01912345608',
                'address' => 'Block D, Mirpur 12, Dhaka-1216',
                'nid_or_passport_number' => '8888888888888',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Mahbub Alam',
                'phone' => '01512345609',
                'address' => 'Flat 2A, Building 5, Dhanmondi 27, Dhaka-1205',
                'nid_or_passport_number' => '9999999999999',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Shahida Parvin',
                'phone' => '01612345610',
                'address' => 'House 18, Road 3, Gulshan 2, Dhaka-1212',
                'nid_or_passport_number' => '1010101010101',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Tariq Islam',
                'phone' => '01712345611',
                'address' => 'Apartment 4B, Building 8, Uttara Sector 7, Dhaka-1230',
                'nid_or_passport_number' => '1212121212121',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Rashida Begum',
                'phone' => '01812345612',
                'address' => 'Flat 6A, Building 15, Mohammadpur, Dhaka-1207',
                'nid_or_passport_number' => '1313131313131',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Kamal Uddin',
                'phone' => '01912345613',
                'address' => 'House 22, Road 5, Baridhara, Dhaka-1212',
                'nid_or_passport_number' => '1414141414141',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Nusrat Jahan',
                'phone' => '01512345614',
                'address' => 'Block E, Mirpur 6, Dhaka-1216',
                'nid_or_passport_number' => '1515151515151',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Abdul Malek',
                'phone' => '01612345615',
                'address' => 'Flat 3C, Building 10, Tejgaon, Dhaka-1208',
                'nid_or_passport_number' => '1616161616161',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($salesAgents as $agent) {
            SalesAgent::create($agent);
        }

        $this->command->info('Sales agents seeded successfully!');
        $this->command->info('Total sales agents created: ' . count($salesAgents));
    }
}
