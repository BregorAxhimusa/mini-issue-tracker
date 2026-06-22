@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <a href="{{ route('projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to projects</a>
            <h1 class="mt-2 text-2xl font-semibold">{{ $project->name }}</h1>
        </div>
        <div class="flex items-center gap-3">
            @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}"
                   class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Edit</a>
            @endcan
            @can('delete', $project)
                <form method="POST" action="{{ route('projects.destroy', $project) }}"
                      onsubmit="return confirm('Delete this project and all of its issues?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="rounded-md border border-red-300 bg-white px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Delete</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="mb-8 rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Description</dt>
                <dd class="mt-1 whitespace-pre-line text-gray-800">{{ $project->description ?: 'No description.' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Start date</dt>
                <dd class="mt-1 text-gray-800">{{ $project->start_date ? $project->start_date->format('M j, Y') : 'Not set' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Deadline</dt>
                <dd class="mt-1 text-gray-800">{{ $project->deadline ? $project->deadline->format('M j, Y') : 'Not set' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Owner</dt>
                <dd class="mt-1 text-gray-800">{{ $project->owner?->name ?? 'Unassigned' }}</dd>
            </div>
        </dl>
    </div>

    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Issues</h2>
        <a href="{{ route('issues.create', ['project_id' => $project->id]) }}"
           class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Create issue for this project
        </a>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Priority</th>
                    <th class="px-4 py-3">Due date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($project->issues as $issue)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <a href="{{ route('issues.show', $issue) }}"
                               class="font-medium text-indigo-600 hover:text-indigo-800">{{ $issue->title }}</a>
                        </td>
                        <td class="px-4 py-3">@include('partials.status-badge', ['status' => $issue->status])</td>
                        <td class="px-4 py-3">@include('partials.priority-badge', ['priority' => $issue->priority])</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $issue->due_date ? $issue->due_date->format('M j, Y') : 'Not set' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">No issues for this project yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
