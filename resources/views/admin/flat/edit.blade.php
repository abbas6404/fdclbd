@extends('admin.layouts.app')

@section('title', 'Edit Flat')

@section('content')
<div class="container-fluid">
    <!-- Livewire Component -->
    @livewire('admin.flat.edit', ['id' => $id])
</div>
@endsection


