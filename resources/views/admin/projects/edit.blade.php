@extends('admin.layouts.app')

@section('title', 'Edit Project')

@section('content')
@livewire('admin.projects.edit', ['project' => $id])
@endsection

