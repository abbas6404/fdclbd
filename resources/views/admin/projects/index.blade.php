@extends('admin.layouts.app')

@section('title', 'Projects Management')

@section('content')
<div class="container-fluid">
    <!-- Livewire Component -->
    @livewire('admin.projects-index')
</div>
@endsection
