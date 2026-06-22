@php
    $styles = [
        'low' => 'bg-gray-100 text-gray-700',
        'medium' => 'bg-indigo-100 text-indigo-800',
        'high' => 'bg-red-100 text-red-800',
    ];
@endphp
<span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $styles[$priority] ?? 'bg-gray-100 text-gray-800' }}">
    {{ ucfirst($priority) }}
</span>
