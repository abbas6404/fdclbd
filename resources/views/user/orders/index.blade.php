@extends('user.layouts.master')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">My Orders</h5>
                </div>
                <div class="card-body">
                    @if(count($orders) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order['id'] }}</td>
                                            <td>{{ $order['date'] }}</td>
                                            <td>${{ number_format($order['total'], 2) }}</td>
                                            <td>
                                                @if($order['status'] == 'Completed')
                                                    <span class="badge bg-success">{{ $order['status'] }}</span>
                                                @elseif($order['status'] == 'Processing')
                                                    <span class="badge bg-warning">{{ $order['status'] }}</span>
                                                @elseif($order['status'] == 'Shipped')
                                                    <span class="badge bg-info">{{ $order['status'] }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $order['status'] }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('orders.show', $order['id']) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> You don't have any orders yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 