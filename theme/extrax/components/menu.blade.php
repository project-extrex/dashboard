<!-- Navbar -->
@if(isset($user_data))
@php
    // Directly call namespaced class
    if (empty(\App\Registry\Menu::get())) {
        \App\Registry\Menu::initial();
    }

    $menus = \App\Registry\Menu::get();
@endphp

<nav class="bg-gray-900 text-white px-4 py-3 relative" x-data="{ open: false }">
    <!-- Logo -->
    <a href="/" class="text-xl font-bold flex items-center text-center gap-2">
        <img src="{{ $favicon }}" width="32" height="32" alt="{{ $siteName }} logo" class="rounded-sm">
        {{ $siteName ?? 'MyApp' }}
    </a>

    <!-- Hamburger (mobile) -->
    <button @click="open = !open" class="sm:hidden text-2xl focus:outline-none absolute right-4 top-3" aria-label="Menu">
        <i :class="open ? 'fas fa-times' : 'fas fa-bars'"></i>
    </button>

    <!-- Menu Items (desktop) -->
    <ul class="hidden sm:flex gap-6 font-medium">
        @foreach($menus as $menu)
            @if(!$menu['admin'] || ($menu['admin'] && $user_data->isAdmin()))
                <li>
                    <a href="{{ $menu['url'] }}" class="hover:text-blue-400 flex items-center gap-1">
                        <i class="{{ $menu['icon'] }}"></i> {{ $menu['title'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>

    <!-- Mobile Menu -->
    <div 
        x-show="open" 
        x-transition 
        class="sm:hidden absolute top-full left-0 w-full bg-gray-800 text-white shadow-md flex flex-col"
        x-cloak
    >
        @foreach($menus as $menu)
            @if(!$menu['admin'] || ($menu['admin'] && $user_data->isAdmin()))
                <a href="{{ $menu['url'] }}" class="px-4 py-3 hover:bg-gray-700 flex items-center gap-2">
                    <i class="{{ $menu['icon'] }}"></i> {{ $menu['title'] }}
                </a>
            @endif
        @endforeach
    </div>
</nav>

<!-- Alpine.js -->
<script src="https://unpkg.com/alpinejs" defer></script>
@endif