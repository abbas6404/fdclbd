@extends('admin.layouts.app')

@section('title', 'Edit Sales Agent')

@section('content')
<div class="container-fluid">
    @livewire('admin.sales-agents.edit', ['id' => $id])
</div>
@endsection

