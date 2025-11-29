@extends('admin.layouts.app')

@section('title', 'Flat Management')

@section('content')
<div class="container-fluid">
    <!-- Livewire Component -->
    @livewire('admin.flat-management.index')
</div>
@endsection