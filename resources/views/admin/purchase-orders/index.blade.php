@extends('admin.layouts.app')

@section('title', isset($edit) ? 'Edit Purchase Order' : 'Create Purchase Order')

@section('content')

<div class="container-fluid">

    @livewire('admin.purchase-orders.index', ['edit' => $edit ?? null])

</div>

@endsection

