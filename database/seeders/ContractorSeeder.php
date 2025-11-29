<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contractor;

class ContractorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contractors = [
            [
                'name' => 'ABC Construction Ltd.',
                'email' => 'info@abcconstruction.com',
                'phone' => '01712345641',
                'address' => 'Plot 10, Industrial Area, Tejgaon, Dhaka-1208',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Premium Builders & Developers',
                'email' => 'contact@premiumbuilders.com',
                'phone' => '01812345642',
                'address' => 'Building 25, Road 12, Dhanmondi, Dhaka-1205',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Green Valley Construction',
                'email' => 'info@greenvalleyconstruction.com',
                'phone' => '01912345643',
                'address' => 'Shop 15, Building 30, Gulshan 1, Dhaka-1212',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Modern Engineering Works',
                'email' => 'sales@modernengineering.com',
                'phone' => '01512345644',
                'address' => 'Block B, Mirpur 10, Dhaka-1216',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Luxury Home Builders',
                'email' => 'info@luxuryhomebuilders.com',
                'phone' => '01612345645',
                'address' => 'House 40, Uttara Sector 3, Dhaka-1230',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Smart Construction Solutions',
                'email' => 'tech@smartconstruction.com',
                'phone' => '01712345646',
                'address' => 'Floor 6, Building 18, Banani, Dhaka-1213',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Elite Builders Group',
                'email' => 'contact@elitebuilders.com',
                'phone' => '01812345647',
                'address' => 'Plot 22, Road 15, Baridhara, Dhaka-1212',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Professional Contractors Ltd.',
                'email' => 'info@professionalcontractors.com',
                'phone' => '01912345648',
                'address' => 'Building 12, Mohammadpur, Dhaka-1207',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Eco-Friendly Builders',
                'email' => 'green@ecofriendlybuilders.com',
                'phone' => '01512345649',
                'address' => 'Shop 20, Building 35, Old Dhaka, Dhaka-1100',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Master Builders & Associates',
                'email' => 'contact@masterbuilders.com',
                'phone' => '01612345650',
                'address' => 'Showroom 5, Road 8, Gulshan 2, Dhaka-1212',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Reliable Construction Co.',
                'email' => 'info@reliableconstruction.com',
                'phone' => '01712345651',
                'address' => 'Floor 3, Building 20, Tejgaon, Dhaka-1208',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Advanced Building Systems',
                'email' => 'sales@advancedbuildings.com',
                'phone' => '01812345652',
                'address' => 'Block D, Mirpur 12, Dhaka-1216',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Quality Construction Services',
                'email' => 'info@qualityconstruction.com',
                'phone' => '01912345653',
                'address' => 'Shop 8, Building 15, Dhanmondi 27, Dhaka-1205',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Prime Builders & Contractors',
                'email' => 'contact@primebuilders.com',
                'phone' => '01512345654',
                'address' => 'Building 28, Uttara Sector 7, Dhaka-1230',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Expert Construction Team',
                'email' => 'info@expertconstruction.com',
                'phone' => '01612345655',
                'address' => 'Plot 15, Industrial Area, Chittagong',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Hassan Construction',
                'email' => null,
                'phone' => '01712345656',
                'address' => 'House 50, Road 20, Gulshan 1, Dhaka-1212',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Rahman Builders',
                'email' => 'rahman@builders.com',
                'phone' => null,
                'address' => 'Block F, Mirpur 6, Dhaka-1216',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'City Construction Works',
                'email' => 'city@construction.com',
                'phone' => '01812345658',
                'address' => null,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($contractors as $contractor) {
            Contractor::create($contractor);
        }

        $this->command->info('Contractors seeded successfully!');
        $this->command->info('Total contractors created: ' . count($contractors));
    }
}
