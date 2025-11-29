@extends('admin.layouts.app')

@section('title', 'Suppliers Management')

@section('content')
<div class="container-fluid">
    @livewire('admin.suppliers.index')
</div>
@endsection
