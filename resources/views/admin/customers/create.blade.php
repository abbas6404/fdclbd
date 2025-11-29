@extends('admin.layouts.app')

@section('title', 'Create Customer - Formonic Design & Construction Ltd')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
                        <li class="breadcrumb-item active">Create Customer</li>
                    </ol>
                </div>
                <h4 class="page-title">Create Customer</h4>
            </div>
        </div>
    </div>

    <!-- Livewire Component -->
    @livewire('admin.customers.create')
</div>
@endsection

