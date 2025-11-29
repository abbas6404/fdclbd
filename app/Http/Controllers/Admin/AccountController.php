<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display the account entry page.
     */
    public function index()
    {
        return view('admin.accounts.index');
    }
}

