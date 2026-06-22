@extends('layouts.app')

@section('title', $issue->title)

@section('content')
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <a href="{{ route('issues.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to issues</a>
            <h1 class="mt-2 text-2xl font-semibold">{{ $issue->title }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('issues.edit', $issue) }}"
               class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Edit</a>
            <form method="POST" action="{{ route('issues.destroy', $issue) }}"
                  onsubmit="return confirm('Delete this issue?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded-md border border-red-300 bg-white px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Delete</button>
            </form>
        </div>
    </div>

    <div class="mb-8 rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Project</dt>
                <dd class="mt-1">
                    <a href="{{ route('projects.show', $issue->project) }}"
                       class="text-indigo-600 hover:text-indigo-800">{{ $issue->project->name }}</a>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Due date</dt>
                <dd class="mt-1 text-gray-800">{{ $issue->due_date ? $issue->due_date->format('M j, Y') : 'Not set' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Status</dt>
                <dd class="mt-1">@include('partials.status-badge', ['status' => $issue->status])</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Priority</dt>
                <dd class="mt-1">@include('partials.priority-badge', ['priority' => $issue->priority])</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Tags</dt>
                <dd class="mt-1">
                    <div id="tag-manager" data-issue-id="{{ $issue->id }}">
                        <div id="current-tags" class="flex flex-wrap gap-2"></div>
                        <div class="mt-3 flex items-center gap-2">
                            <select id="tag-select"
                                    class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a tag</option>
                            </select>
                            <button type="button" id="attach-tag"
                                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Add tag</button>
                        </div>
                    </div>
                    <script type="application/json" id="current-tags-data">@json($issue->tags->map(fn ($tag) => ['id' => $tag->id, 'name' => $tag->name, 'color' => $tag->color]))</script>
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Description</dt>
                <dd class="mt-1 whitespace-pre-line text-gray-800">{{ $issue->description ?: 'No description.' }}</dd>
            </div>
        </dl>
    </div>

    <section>
        <h2 class="mb-4 text-lg font-semibold">Comments</h2>

        <div id="comments" data-issue-id="{{ $issue->id }}" class="space-y-6">
            <form id="comment-form" class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="author_name" class="block text-sm font-medium text-gray-700">Your name</label>
                        <input type="text" id="author_name" name="author_name"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-sm text-red-600" data-error="author_name"></p>
                    </div>
                    <div>
                        <label for="body" class="block text-sm font-medium text-gray-700">Comment</label>
                        <textarea id="body" name="body" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <p class="mt-1 text-sm text-red-600" data-error="body"></p>
                    </div>
                    <div>
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Add comment</button>
                    </div>
                </div>
            </form>

            <div id="comment-list" class="rounded-lg bg-white px-6 shadow-sm ring-1 ring-gray-200"></div>

            <div class="text-center">
                <button type="button" id="load-more" hidden
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Load more</button>
            </div>
        </div>
    </section>
@endsection
