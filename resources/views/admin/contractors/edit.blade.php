@extends('admin.layouts.app')

@section('title', 'Edit Contractor')

@section('content')
    @livewire('admin.contractors.edit', ['id' => $id])
@endsection

