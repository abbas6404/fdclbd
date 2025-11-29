<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;

class DisplaySettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'permission:setup.display']);
    }

    /**
     * Display the display settings page.
     */
    public function index()
    {
        // Get all public system settings
        $systemSettings = SystemSetting::where('is_public', true)
            ->orderBy('group')
            ->orderBy('key')
            ->get();
        
        return view('admin.setup.display-settings.index', compact('systemSettings'));
    }

    /**
     * Show the form for editing display settings.
     */
    public function edit()
    {
        // Get all public system settings
        $systemSettings = SystemSetting::where('is_public', true)
            ->orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy('group');
        
        return view('admin.setup.display-settings.edit', compact('systemSettings'));
    }

    /**
     * Update display settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        try {
            foreach ($request->settings as $id => $data) {
                $setting = SystemSetting::where('id', $id)
                    ->where('is_public', true)
                    ->first();
                
                if ($setting) {
                    // Handle checkbox values
                    if ($setting->type === 'boolean') {
                        $setting->value = isset($data['value']) ? '1' : '0';
                    } else {
                        $setting->value = $data['value'] ?? '';
                    }
                    $setting->save();
                }
            }

            return redirect()->route('admin.setup.display.index')
                ->with('success', 'Display settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }
}

