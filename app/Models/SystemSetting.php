<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get a setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $cacheKey = "system_setting_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            // Cast value based on type
            switch ($setting->type) {
                case 'boolean':
                    return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
                case 'integer':
                    return (int) $setting->value;
                case 'float':
                case 'decimal':
                    return (float) $setting->value;
                case 'array':
                case 'json':
                    return json_decode($setting->value, true) ?: $default;
                default:
                    return $setting->value;
            }
        });
    }

    /**
     * Set a setting value
     */
    public static function setValue($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description
            ]
        );

        // Clear cache for this setting
        Cache::forget("system_setting_{$key}");
        
        return $setting;
    }

    /**
     * Force refresh cache for a specific setting
     */
    public static function refreshCache($key)
    {
        Cache::forget("system_setting_{$key}");
        return self::getValue($key);
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get();
    }

    /**
     * Get settings as key-value array
     */
    public static function getAsArray($group = null)
    {
        $query = self::query();
        
        if ($group) {
            $query->where('group', $group);
        }
        
        return $query->pluck('value', 'key')->toArray();
    }

    /**
     * Delete a setting
     */
    public static function deleteSetting($key)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            Cache::forget("system_setting_{$key}");
            return $setting->delete();
        }
        
        return false;
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $settings = self::all();
        
        foreach ($settings as $setting) {
            Cache::forget("system_setting_{$setting->key}");
        }
    }

    /**
     * Get prefix settings
     */
    public static function getPrefixSettings()
    {
        return self::getByGroup('prefix');
    }

    /**
     * Get system settings
     */
    public static function getSystemSettings()
    {
        return self::getByGroup('system');
    }

    /**
     * Get maintenance settings
     */
    public static function getMaintenanceSettings()
    {
        return self::getByGroup('maintenance');
    }

    /**
     * Get general settings
     */
    public static function getGeneralSettings()
    {
        return self::getByGroup('general');
    }
}
