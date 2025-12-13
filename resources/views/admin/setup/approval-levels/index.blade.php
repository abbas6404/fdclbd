@extends('admin.setup.setup-layout')

@section('page-title', 'Approval Levels')
@section('page-description', 'Manage requisition approval levels and assign users')

@section('setup-content')
<div class="card shadow">
    <div class="card-body">
        @livewire('admin.setup.approval-levels')
    </div>
</div>
@endsection

