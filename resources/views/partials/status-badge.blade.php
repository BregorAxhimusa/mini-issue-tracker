@php
    $styles = [
        'open' => 'bg-blue-100 text-blue-800',
        'in_progress' => 'bg-amber-100 text-amber-800',
        'closed' => 'bg-green-100 text-green-800',
    ];
    $labels = [
        'open' => 'Open',
        'in_progress' => 'In progress',
        'closed' => 'Closed',
    ];
@endphp
<span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $styles[$status] ?? 'bg-gray-100 text-gray-800' }}">
    {{ $labels[$status] ?? $status }}
</span>
