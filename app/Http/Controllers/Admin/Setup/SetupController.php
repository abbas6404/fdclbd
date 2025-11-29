<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'permission:setup.dashboard']);     
    }

    /**
     * Display the main setup page.
     */
    public function index()
    {
        return view('admin.setup.index');
    }
} 