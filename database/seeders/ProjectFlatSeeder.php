<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectFlat;
use App\Models\Project;

class ProjectFlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        
        foreach ($projects as $project) {
            $this->createFlatsForProject($project);
        }

        $this->command->info('Project flats seeded successfully!');
        $this->command->info('Total flats created: ' . ProjectFlat::count());
    }

    private function createFlatsForProject($project)
    {
        $flatTypes = ['Studio', '1BHK', '2BHK', '3BHK', '4BHK', 'Penthouse'];
        $statuses = ['available', 'sold', 'reserved'];
        
        // Create a reasonable number of flats per project (default 10 floors, 4 units per floor = 40 flats)
        $floors = 10;
        $unitsPerFloor = 4;
        
        $flatCounter = 1;
        
        for ($floor = 1; $floor <= $floors; $floor++) {
            for ($unit = 1; $unit <= $unitsPerFloor; $unit++) {
                $flatType = $flatTypes[array_rand($flatTypes)];
                $flatSize = $this->getFlatSize($flatType);
                $status = $statuses[array_rand($statuses)];
                
                ProjectFlat::create([
                    'project_id' => $project->id,
                    'flat_number' => $this->generateFlatNumber($floor, $unit),
                    'flat_type' => $flatType,
                    'floor_number' => $floor,
                    'flat_size' => $flatSize,
                    'status' => $status,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
                
                $flatCounter++;
            }
        }
    }

    private function generateFlatNumber($floor, $unit)
    {
        $floorLetter = chr(64 + $floor); // A, B, C, etc.
        return $floorLetter . '-' . str_pad($unit, 2, '0', STR_PAD_LEFT);
    }

    private function getFlatSize($flatType)
    {
        $sizes = [
            'Studio' => rand(400, 600),
            '1BHK' => rand(600, 800),
            '2BHK' => rand(800, 1200),
            '3BHK' => rand(1200, 1800),
            '4BHK' => rand(1800, 2500),
            'Penthouse' => rand(2500, 4000),
        ];
        
        return $sizes[$flatType];
    }

}
