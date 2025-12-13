<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApprovalLevelController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'permission:setup.approval-levels']);
    }

    /**
     * Display the approval levels setup page.
     */
    public function index()
    {
        return view('admin.setup.approval-levels.index');
    }
}
