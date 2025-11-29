@extends('admin.layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<div class="container-fluid">
    @livewire('admin.suppliers.edit', ['id' => $id])
</div>
@endsection

