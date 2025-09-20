<!-- Navbar -->
@if(isset($user_data))
<nav class="bg-gray-900 text-white px-4 py-3 relative" x-data="{ open: false }">
    <!-- Logo -->
    <a href="/" class="text-xl font-bold flex align-items-center text-center gap-2"><img src="{{$favicon}}" width="32" height="32" alt="{{$siteName}} logo" class="rounded-sm"> {{ $siteName ?? 'MyApp' }}</a>

    <!-- Hamburger (mobile) -->
    <button @click="open = !open" class="sm:hidden text-2xl focus:outline-none absolute right-4 top-3" aria-label="Menu">
        <i :class="open ? 'fas fa-times' : 'fas fa-bars'"></i>
    </button>

    <!-- Menu Items (desktop) -->
    <ul class="hidden sm:flex gap-6 font-medium">
        <li><a href="/" class="hover:text-blue-400">Home</a></li>
        <li><a href="/dashboard" class="hover:text-blue-400">Dashboard</a></li>
        <li><a href="/profile" class="hover:text-blue-400">Profile</a></li>
        <li><a href="/logout" class="hover:text-red-400">Logout</a></li>
    </ul>

    <!-- Mobile Menu -->
    <div 
        x-show="open" 
        x-transition 
        class="sm:hidden absolute top-full left-0 w-full bg-gray-800 text-white shadow-md flex flex-col"
        x-cloak
    >
        <a href="/" class="px-4 py-3 hover:bg-gray-700">Home</a>
        <a href="/dashboard" class="px-4 py-3 hover:bg-gray-700">Dashboard</a>
        <a href="/profile" class="px-4 py-3 hover:bg-gray-700">Profile</a>
        @if($user_data->isAdmin())
          <a href="/admin" class="px-4 py-3 hover:bg-gray-700">Admin</a>
        @endif
        <a href="/logout" class="px-4 py-3 hover:bg-gray-700">Logout</a>
    </div>
</nav>

<!-- Alpine.js -->
<script src="https://unpkg.com/alpinejs" defer></script>
@endif