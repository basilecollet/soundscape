@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Content Management</h1>
        
        @livewire('admin.content-list')
    </div>
@endsection