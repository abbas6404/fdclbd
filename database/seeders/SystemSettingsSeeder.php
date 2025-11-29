<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing corrupted data    
        SystemSetting::truncate();
        
        // Super Admin Role Display Setting  here 0 means hide and 1 means show 
        SystemSetting::create(['id' => 1, 'key' => 'super_admin_role_display', 'value' => '0', 'type' => 'integer', 'group' => 'system', 'description' => 'Super Admin role display in role list', 'is_public' => false]);
      
        // System Settings
        SystemSetting::create(['id' => 2, 'key' => 'app_version', 'value' => '1.0.0', 'type' => 'string', 'group' => 'system', 'description' => 'Application version', 'is_public' => false]);
        SystemSetting::create(['id' => 3, 'key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group' => 'system', 'description' => 'Maintenance mode status', 'is_public' => false]);
        SystemSetting::create(['id' => 63, 'key' => 'currency_symbol', 'value' => '', 'type' => 'string', 'group' => 'system', 'description' => 'System currency symbol', 'is_public' => true]);
      



        $this->command->info('System settings seeded successfully with proper is_public values and IDs!');
    }
}
