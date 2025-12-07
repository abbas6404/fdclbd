<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BoqController extends Controller
{
    /**
     * Display the BOQ page.
     */
    public function index()
    {
        return view('admin.boq.index');
    }
}

