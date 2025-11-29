@extends('admin.layouts.app')

@section('title', 'Customer Management')

@section('content')
<div class="container-fluid">
    @livewire('admin.customers.index')
</div>
@endsection