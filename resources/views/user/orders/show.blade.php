@extends('user.layouts.master')

@section('title', 'Order Details')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order #{{ $order['id'] }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Order Date:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $order['date'] }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-8">
                            @if($order['status'] == 'Completed')
                                <span class="badge bg-success">{{ $order['status'] }}</span>
                            @elseif($order['status'] == 'Processing')
                                <span class="badge bg-warning">{{ $order['status'] }}</span>
                            @elseif($order['status'] == 'Shipped')
                                <span class="badge bg-info">{{ $order['status'] }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $order['status'] }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Total:</strong>
                        </div>
                        <div class="col-md-8">
                            ${{ number_format($order['total'], 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order['items'] as $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>${{ number_format($item['price'], 2) }}</td>
                                        <td>${{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal:</th>
                                    <td>${{ number_format($order['total'], 2) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <td><strong>${{ number_format($order['total'], 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i> Download Invoice
                        </a>
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="fas fa-print me-1"></i> Print Order
                        </a>
                        @if($order['status'] != 'Completed')
                            <a href="#" class="btn btn-outline-danger">
                                <i class="fas fa-times-circle me-1"></i> Cancel Order
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Need Help?</h5>
                </div>
                <div class="card-body">
                    <p>If you have any questions about your order, please contact our customer support:</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('contact') }}" class="btn btn-primary">
                            <i class="fas fa-envelope me-1"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 