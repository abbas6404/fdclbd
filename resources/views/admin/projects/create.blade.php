@extends('admin.layouts.app')

@section('title', 'Add New Project')

@section('content')
<div class="container-fluid">
    <!-- Livewire Component -->
    @livewire('admin.projects.create')
</div>
@endsection
