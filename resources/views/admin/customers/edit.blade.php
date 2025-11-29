@extends('admin.layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="container-fluid">
    @livewire('admin.customers.edit', ['id' => $id])
</div>
@endsection

