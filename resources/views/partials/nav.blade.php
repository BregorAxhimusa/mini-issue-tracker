<nav class="border-b border-gray-200 bg-white">
    <div class="mx-auto flex h-14 max-w-5xl items-center justify-between px-4">
        <a href="{{ url('/') }}" class="font-semibold text-gray-900">{{ config('app.name') }}</a>

        <div class="flex gap-6 text-sm">
            <a href="{{ route('projects.index') }}"
               class="{{ request()->routeIs('projects.*') ? 'font-medium text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                Projects
            </a>
            <a href="{{ route('issues.index') }}"
               class="{{ request()->routeIs('issues.*') ? 'font-medium text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                Issues
            </a>
        </div>
    </div>
</nav>
