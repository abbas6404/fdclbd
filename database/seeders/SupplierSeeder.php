<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Dhaka Construction Materials Ltd.',
                'email' => 'info@dhakaconstruction.com',
                'phone' => '01712345621',
                'address' => 'Plot 25, Industrial Area, Tejgaon, Dhaka-1208',
                'description' => 'Leading supplier of cement, steel, and construction materials',
            ],
            [
                'name' => 'Premium Tiles & Marble Co.',
                'email' => 'sales@premiumtiles.com',
                'phone' => '01812345622',
                'address' => 'Building 15, Road 7, Dhanmondi, Dhaka-1205',
                'description' => 'Specialized in premium tiles, marble, and granite',
            ],
            [
                'name' => 'Green Valley Electricals',
                'email' => 'contact@greenvalleyelectricals.com',
                'phone' => '01912345623',
                'address' => 'Shop 8, Building 20, Gulshan 1, Dhaka-1212',
                'description' => 'Electrical equipment and wiring supplies',
            ],
            [
                'name' => 'Modern Plumbing Solutions',
                'email' => 'info@modernplumbing.com',
                'phone' => '01512345624',
                'address' => 'Block A, Mirpur 10, Dhaka-1216',
                'description' => 'Complete plumbing solutions and fixtures',
            ],
            [
                'name' => 'Luxury Paint & Coating',
                'email' => 'sales@luxurypaint.com',
                'phone' => '01612345625',
                'address' => 'House 30, Uttara Sector 3, Dhaka-1230',
                'description' => 'Premium paints, coatings, and decorative materials',
            ],
            [
                'name' => 'Smart Home Technologies',
                'email' => 'tech@smarthome.com',
                'phone' => '01712345626',
                'address' => 'Floor 5, Building 12, Banani, Dhaka-1213',
                'description' => 'Smart home automation and security systems',
            ],
            [
                'name' => 'Garden Landscaping Co.',
                'email' => 'info@gardenlandscaping.com',
                'phone' => '01812345627',
                'address' => 'Plot 18, Road 11, Baridhara, Dhaka-1212',
                'description' => 'Landscaping, gardening, and outdoor design',
            ],
            [
                'name' => 'Premium Glass & Aluminum',
                'email' => 'sales@premiumglass.com',
                'phone' => '01912345628',
                'address' => 'Building 8, Mohammadpur, Dhaka-1207',
                'description' => 'Glass, aluminum, and window solutions',
            ],
            [
                'name' => 'Eco-Friendly Materials',
                'email' => 'green@ecofriendly.com',
                'phone' => '01512345629',
                'address' => 'Shop 12, Building 25, Old Dhaka, Dhaka-1100',
                'description' => 'Sustainable and eco-friendly building materials',
            ],
            [
                'name' => 'Luxury Furniture & Interiors',
                'email' => 'furniture@luxuryinteriors.com',
                'phone' => '01612345630',
                'address' => 'Showroom 3, Road 5, Gulshan 2, Dhaka-1212',
                'description' => 'Premium furniture and interior design solutions',
            ],
            [
                'name' => 'Security Systems Ltd.',
                'email' => 'security@securitysystems.com',
                'phone' => '01712345631',
                'address' => 'Floor 2, Building 15, Tejgaon, Dhaka-1208',
                'description' => 'CCTV, access control, and security systems',
            ],
            [
                'name' => 'HVAC Solutions',
                'email' => 'hvac@hvacsolutions.com',
                'phone' => '01812345632',
                'address' => 'Block C, Mirpur 12, Dhaka-1216',
                'description' => 'Heating, ventilation, and air conditioning systems',
            ],
            [
                'name' => 'Flooring Specialists',
                'email' => 'info@flooringspecialists.com',
                'phone' => '01912345633',
                'address' => 'Shop 5, Building 10, Dhanmondi 27, Dhaka-1205',
                'description' => 'Specialized flooring solutions and installation',
            ],
            [
                'name' => 'Lighting & Fixtures Co.',
                'email' => 'lighting@lightingfixtures.com',
                'phone' => '01512345634',
                'address' => 'Building 20, Uttara Sector 7, Dhaka-1230',
                'description' => 'Decorative and functional lighting solutions',
            ],
            [
                'name' => 'Water Treatment Systems',
                'email' => 'water@watertreatment.com',
                'phone' => '01612345635',
                'address' => 'Plot 12, Industrial Area, Chittagong',
                'description' => 'Water purification and treatment systems',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        $this->command->info('Suppliers seeded successfully!');
        $this->command->info('Total suppliers created: ' . count($suppliers));
    }
}
