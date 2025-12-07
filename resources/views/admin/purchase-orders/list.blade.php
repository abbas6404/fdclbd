@extends('admin.layouts.app')

@section('title', 'Purchase Orders List')

@section('content')

<div class="container-fluid">

    @livewire('admin.purchase-orders.list-page')

</div>

@endsection

