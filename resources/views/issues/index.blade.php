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

    <form method="GET" action="{{ route('issues.index') }}"
          class="mb-6 flex flex-wrap items-end gap-3 rounded-lg bg-white p-4 shadow-sm ring-1 ring-gray-200">
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

    <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Project</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Priority</th>
                    <th class="px-4 py-3">Due date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($issues as $issue)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <a href="{{ route('issues.show', $issue) }}"
                               class="font-medium text-indigo-600 hover:text-indigo-800">{{ $issue->title }}</a>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $issue->project->name }}</td>
                        <td class="px-4 py-3">@include('partials.status-badge', ['status' => $issue->status])</td>
                        <td class="px-4 py-3">@include('partials.priority-badge', ['priority' => $issue->priority])</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $issue->due_date ? $issue->due_date->format('M j, Y') : 'Not set' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No issues match these filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $issues->links() }}
    </div>
@endsection
