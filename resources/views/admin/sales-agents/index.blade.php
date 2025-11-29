@extends('admin.layouts.app')

@section('title', 'Sales Agent Management')

@section('content')
<div class="container-fluid">
    <!-- Livewire Component -->
    @livewire('admin.sales-agents.index')
</div>
@endsection
