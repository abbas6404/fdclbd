<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SystemSettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'permission:setup.system-settings']);
    }

    /**
     * Display the system settings page.
     */
    public function index()
    {
        // Get all system settings grouped by group
        $systemSettings = SystemSetting::orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy('group');
        
        return view('admin.setup.system-settings.index', compact('systemSettings'));
    }
}

