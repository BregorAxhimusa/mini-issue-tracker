@extends('layouts.app')

@section('title', 'Issues')

@section('content')
    @php
        $statuses = ['open' => 'Open', 'in_progress' => 'In progress', 'closed' => 'Closed'];
        $priorities = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];
    @endphp

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Issues</h1>
        <a href="{{ route('issues.create') }}"
           class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Create issue
        </a>
    </div>

    <form method="GET" action="{{ route('issues.index') }}" id="issue-filter-form"
          class="mb-6 flex flex-wrap items-end gap-3 rounded-lg bg-white p-4 shadow-sm ring-1 ring-gray-200">
        <div class="grow">
            <label for="search" class="block text-xs font-medium text-gray-500">Search</label>
            <input type="search" id="search" name="search" value="{{ request('search') }}"
                   placeholder="Search title or description"
                   class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label for="status" class="block text-xs font-medium text-gray-500">Status</label>
            <select id="status" name="status"
                    class="mt-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="priority" class="block text-xs font-medium text-gray-500">Priority</label>
            <select id="priority" name="priority"
                    class="mt-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All</option>
                @foreach ($priorities as $value => $label)
                    <option value="{{ $value }}" @selected(request('priority') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="tag_id" class="block text-xs font-medium text-gray-500">Tag</label>
            <select id="tag_id" name="tag_id"
                    class="mt-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" @selected((string) request('tag_id') === (string) $tag->id)>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-900">Filter</button>
            <a href="{{ route('issues.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Reset</a>
        </div>
    </form>

    <div id="issue-results">
        @include('issues.partials.list')
    </div>
@endsection
