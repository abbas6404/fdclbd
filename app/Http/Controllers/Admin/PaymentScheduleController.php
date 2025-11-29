<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentScheduleController extends Controller
{
    /**
     * Display the payment schedule page.
     */
    public function index()
    {
        return view('admin.payment-schedules.index');
    }
}

