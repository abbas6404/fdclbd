@extends('admin.layouts.app')

@section('title', 'Add New Flat')

@section('content')
<div class="container-fluid">
    <!-- Livewire Component -->
    @livewire('admin.flat-management.create', ['project_id' => request()->query('project_id')])
</div>
@endsection
