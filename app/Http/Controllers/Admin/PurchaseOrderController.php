<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $edit = $request->get('edit');
        return view('admin.purchase-orders.index', ['edit' => $edit]);
    }

    public function list()
    {
        return view('admin.purchase-orders.list');
    }
}

