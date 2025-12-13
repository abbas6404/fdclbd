<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApprovalLevel;

class ApprovalLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Fast',
                'slug' => 'fast',
                'sequence' => 1,
                'description' => 'First level approval',
                'is_active' => true,
            ],
            [
                'name' => 'Director',
                'slug' => 'director',
                'sequence' => 2,
                'description' => 'Director level approval',
                'is_active' => true,
            ],
            [
                'name' => 'Chairman',
                'slug' => 'chairman',
                'sequence' => 3,
                'description' => 'Chairman level approval',
                'is_active' => true,
            ],
            [
                'name' => 'Managing Director',
                'slug' => 'managing_director',
                'sequence' => 4,
                'description' => 'Managing Director level approval',
                'is_active' => true,
            ],
        ];

        foreach ($levels as $level) {
            ApprovalLevel::updateOrCreate(
                ['slug' => $level['slug']],
                $level
            );
        }

        $this->command->info('Approval levels seeded successfully!');
    }
}
