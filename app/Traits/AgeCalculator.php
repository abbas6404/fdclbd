<?php

namespace App\Traits;

use Carbon\Carbon;

trait AgeCalculator
{
    /**
     * Calculate age from date of birth.
     *
     * @param  string|null  $dob
     * @return array
     */
    public function calculateAge($dob)
    {
        if (!$dob) {
            return ['years' => 0, 'months' => 0, 'days' => 0];
        }

        try {
            $dob = Carbon::parse($dob);
            $now = Carbon::now();
            
            // If date of birth is in the future, return 0
            if ($dob->isFuture()) {
                return ['years' => 0, 'months' => 0, 'days' => 0];
            }
            
            // Calculate the difference
            $diff = $now->diff($dob);
            
            return [
                'years' => max(0, $diff->y),
                'months' => max(0, $diff->m),
                'days' => max(0, $diff->d)
            ];
        } catch (\Exception $e) {
            return ['years' => 0, 'months' => 0, 'days' => 0];
        }
    }

    /**
     * Format age display for templates.
     *
     * @param  int|null  $years
     * @param  int|null  $months
     * @param  int|null  $days
     * @return string
     */
    public function formatAge($years, $months, $days)
    {
        $years = $years ?? 0;
        $months = $months ?? 0;
        $days = $days ?? 0;
        
        $ageParts = [];
        
        if ($years > 0) {
            $ageParts[] = $years . ' year' . ($years > 1 ? 's' : '');
        }
        if ($months > 0) {
            $ageParts[] = $months . ' month' . ($months > 1 ? 's' : '');
        }
        if ($days > 0) {
            $ageParts[] = $days . ' day' . ($days > 1 ? 's' : '');
        }
        
        if (empty($ageParts)) {
            return 'N/A';
        }
        
        return implode(' ', $ageParts);
    }

    /**
     * Calculate date of birth from age components.
     * Handles cases like: 5 days, 1 month 2 days, 1 year 1 month 3 days
     *
     * @param  int|null  $years
     * @param  int|null  $months
     * @param  int|null  $days
     * @return string|null
     */
    public function calculateDobFromAge($years = null, $months = null, $days = null)
    {
        // Convert to integers and handle null values
        $years = intval($years) ?: 0;
        $months = intval($months) ?: 0;
        $days = intval($days) ?: 0;
        
        // If all are 0, return null
        if ($years === 0 && $months === 0 && $days === 0) {
            return null;
        }
        
        try {
            $now = Carbon::now();
            
            // Handle special case: only days (like 5 day baby)
            if ($years === 0 && $months === 0 && $days > 0) {
                return $now->subDays($days)->format('Y-m-d');
            }
            
            // Handle case: only months
            if ($years === 0 && $months > 0 && $days === 0) {
                return $now->subMonths($months)->format('Y-m-d');
            }
            
            // Handle case: only years
            if ($years > 0 && $months === 0 && $days === 0) {
                return $now->subYears($years)->format('Y-m-d');
            }
            
            // Handle mixed cases (like 1 month 2 days, 1 year 1 month 3 days)
            if ($years > 0) {
                $now = $now->subYears($years);
            }
            
            if ($months > 0) {
                $now = $now->subMonths($months);
            }
            
            if ($days > 0) {
                $now = $now->subDays($days);
            }
            
            return $now->format('Y-m-d');
            
        } catch (\Exception $e) {
            return null;
        }
    }
} 