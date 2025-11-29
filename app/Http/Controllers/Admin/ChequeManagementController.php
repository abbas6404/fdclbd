<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChequeManagementController extends Controller
{
    /**
     * Display the cheque management page.
     */
    public function index()
    {
        return view('admin.cheque-management.index');
    }
}
