<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'project_name' => 'Green Valley Residency',
                'description' => 'A modern residential complex with premium amenities and eco-friendly design.',
                'address' => 'Plot 15, Block A, Dhanmondi, Dhaka-1205',
                'facing' => 'South',
                'total_floors' => 12,
                'land_area' => 25000.00,
                'project_launching_date' => '2024-01-15',
                'project_hand_over_date' => '2026-06-30',
                'status' => 'ongoing',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'project_name' => 'Royal Garden Apartments',
                'description' => 'Luxury apartments with panoramic city views and world-class facilities.',
                'address' => 'Road 7, Gulshan 1, Dhaka-1212',
                'facing' => 'East',
                'total_floors' => 15,
                'land_area' => 30000.00,
                'project_launching_date' => '2023-08-20',
                'project_hand_over_date' => '2025-12-15',
                'status' => 'ongoing',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'project_name' => 'Sunrise Heights',
                'description' => 'Affordable housing project with modern amenities for middle-class families.',
                'address' => 'Block C, Mirpur 10, Dhaka-1216',
                'facing' => 'North',
                'total_floors' => 9,
                'land_area' => 18000.00,
                'project_launching_date' => '2024-03-10',
                'project_hand_over_date' => '2026-09-30',
                'status' => 'upcoming',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'project_name' => 'Ocean View Tower',
                'description' => 'Premium beachfront apartments with stunning ocean views.',
                'address' => 'Cox\'s Bazar Sea Beach Road, Cox\'s Bazar',
                'facing' => 'West',
                'total_floors' => 20,
                'land_area' => 50000.00,
                'project_launching_date' => '2023-12-01',
                'project_hand_over_date' => '2027-03-31',
                'status' => 'ongoing',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'project_name' => 'Tech Park Residences',
                'description' => 'Modern apartments designed for tech professionals with smart home features.',
                'address' => 'Uttara Sector 3, Dhaka-1230',
                'facing' => 'South-East',
                'total_floors' => 11,
                'land_area' => 22000.00,
                'project_launching_date' => '2024-06-01',
                'project_hand_over_date' => '2026-12-31',
                'status' => 'on_hold',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'project_name' => 'Heritage Plaza',
                'description' => 'Completed luxury project with traditional architectural elements.',
                'address' => 'Old Dhaka, Chawkbazar, Dhaka-1100',
                'facing' => 'North-West',
                'total_floors' => 8,
                'land_area' => 15000.00,
                'project_launching_date' => '2022-01-01',
                'project_hand_over_date' => '2024-01-01',
                'status' => 'completed',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        $this->command->info('Projects seeded successfully!');
        $this->command->info('Total projects created: ' . count($projects));
    }
}
