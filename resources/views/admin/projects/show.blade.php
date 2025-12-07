@extends('admin.layouts.app')

@section('title', 'Project Details')

@section('content')
@livewire('admin.projects.show', ['project' => $id])
@endsection

