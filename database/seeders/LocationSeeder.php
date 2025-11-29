<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['name' => 'Dhaka', 'type' => 'division'],
            ['name' => 'Chattogram', 'type' => 'division'],
            ['name' => 'Sylhet', 'type' => 'division'],
        ];
        $districts = [
            ['name' => 'Dhaka', 'type' => 'district', 'division_id' => 1],
            ['name' => 'Chattogram', 'type' => 'district', 'division_id' => 2],
            ['name' => 'Sylhet', 'type' => 'district', 'division_id' => 3],
        ];
        $upazilas = [
            ['name' => 'Dhaka', 'type' => 'upazila', 'district_id' => 1],
            ['name' => 'Chattogram', 'type' => 'upazila', 'district_id' => 2],
            ['name' => 'Sylhet', 'type' => 'upazila', 'district_id' => 3],
        ];  
       
    }
}
