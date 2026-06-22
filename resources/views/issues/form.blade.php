@php
    $editing = isset($issue);
    $statuses = ['open' => 'Open', 'in_progress' => 'In progress', 'closed' => 'Closed'];
    $priorities = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];
    $selectedProject = old('project_id', $issue->project_id ?? request('project_id'));
@endphp

<form method="POST"
      action="{{ $editing ? route('issues.update', $issue) : route('issues.store') }}"
      class="space-y-5">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div>
        <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
        <select id="project_id" name="project_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Select a project</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected((int) $selectedProject === $project->id)>{{ $project->name }}</option>
            @endforeach
        </select>
        @error('project_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
        <input type="text" id="title" name="title"
               value="{{ old('title', $issue->title ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea id="description" name="description" rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $issue->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status" name="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $issue->status ?? 'open') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
            <select id="priority" name="priority"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach ($priorities as $value => $label)
                    <option value="{{ $value }}" @selected(old('priority', $issue->priority ?? 'medium') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('priority')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="due_date" class="block text-sm font-medium text-gray-700">Due date</label>
            <input type="date" id="due_date" name="due_date"
                   value="{{ old('due_date', $editing && $issue->due_date ? $issue->due_date->format('Y-m-d') : '') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('due_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            {{ $editing ? 'Save changes' : 'Create issue' }}
        </button>
        <a href="{{ $editing ? route('issues.show', $issue) : route('issues.index') }}"
           class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</form>
