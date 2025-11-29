<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the user's orders.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Here you would typically fetch the user's orders from the database
        // For now, we'll just return a view with sample data
        $orders = [
            [
                'id' => 'ORD-001',
                'date' => '2023-05-15',
                'total' => 125.50,
                'status' => 'Completed'
            ],
            [
                'id' => 'ORD-002',
                'date' => '2023-06-20',
                'total' => 75.25,
                'status' => 'Processing'
            ],
            [
                'id' => 'ORD-003',
                'date' => '2023-07-10',
                'total' => 210.00,
                'status' => 'Shipped'
            ],
        ];
        
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        // Here you would typically fetch the specific order from the database
        // For now, we'll just return a view with sample data
        $order = [
            'id' => $id,
            'date' => '2023-07-10',
            'total' => 210.00,
            'status' => 'Shipped',
            'items' => [
                [
                    'name' => 'Product 1',
                    'quantity' => 2,
                    'price' => 45.00
                ],
                [
                    'name' => 'Product 2',
                    'quantity' => 1,
                    'price' => 120.00
                ]
            ]
        ];
        
        return view('user.orders.show', compact('order'));
    }
} 