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
