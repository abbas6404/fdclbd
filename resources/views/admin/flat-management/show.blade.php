@extends('admin.layouts.app')

@section('title', 'Flat Details')

@section('content')
<div class="container-fluid">
    <!-- Livewire Component -->
    @livewire('admin.flat-management.show', ['id' => $id])
</div>
@endsection

