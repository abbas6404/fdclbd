@extends('admin.layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item active">Edit Project</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Project</h4>
            </div>
        </div>
    </div>

    <!-- Livewire Component -->
    @livewire('admin.projects.edit', ['project' => $id])
</div>
@endsection

