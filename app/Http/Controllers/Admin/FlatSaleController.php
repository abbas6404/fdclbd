<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlatSaleController extends Controller
{
    /**
     * Display the flat sales page.
     */
    public function index()
    {
        return view('admin.flat-sales.index');
    }
}

