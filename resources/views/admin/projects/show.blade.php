@extends('admin.layouts.app')

@section('title', 'Project Details')

@section('content')
<div class="container-fluid">
    <!-- Livewire Component -->
    @livewire('admin.projects.show', ['project' => $id])
</div>
@endsection

