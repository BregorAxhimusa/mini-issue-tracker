@extends('layouts.app')

@section('title', 'Create issue')

@section('content')
    <div class="mb-6">
        <a href="{{ route('issues.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to issues</a>
        <h1 class="mt-2 text-2xl font-semibold">Create issue</h1>
    </div>

    <div class="max-w-2xl rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
        @include('issues.form')
    </div>
@endsection
