<nav class="border-b border-gray-200 bg-white">
    <div class="mx-auto flex h-14 max-w-5xl items-center justify-between px-4">
        <a href="{{ url('/') }}" class="font-semibold text-gray-900">{{ config('app.name') }}</a>

        <div class="flex items-center gap-6 text-sm">
            <a href="{{ route('projects.index') }}"
               class="{{ request()->routeIs('projects.*') ? 'font-medium text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                Projects
            </a>
            <a href="{{ route('issues.index') }}"
               class="{{ request()->routeIs('issues.*') ? 'font-medium text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                Issues
            </a>

            @auth
                <span class="text-gray-400">|</span>
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-900">Log out</button>
                </form>
            @else
                <span class="text-gray-400">|</span>
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Log in</a>
                <a href="{{ route('register') }}"
                   class="rounded-md bg-indigo-600 px-3 py-1.5 font-medium text-white hover:bg-indigo-700">Register</a>
            @endauth
        </div>
    </div>
</nav>
