<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentReceiveController extends Controller
{
    /**
     * Display the payment receive page.
     */
    public function index()
    {
        return view('admin.payment-receive.index');
    }
}

