@props(['user' => null, 'onMenuClick' => null])

<header class="h-16 bg-white/80 backdrop-blur-md border-b border-gray-100 px-4 md:px-8 flex items-center justify-between sticky top-0 z-50">
    <!-- Sisi Kiri: Logo & Menu Mobile -->
    <div class="flex items-center gap-4">
        @if($onMenuClick)
        <button
            @click="{{ $onMenuClick }}"
            class="md:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-xl transition-all active:scale-95"
        >
        @else
        <button
            class="md:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-xl transition-all active:scale-95"
        >
        @endif
            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <div class="flex flex-col">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="text-lg font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent hover:from-blue-700 hover:to-indigo-700 transition-all hidden md:flex items-center">
                        <span>Sistem Absen</span>
                        <svg class="ml-2 fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('dashboard')">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </x-dropdown-link>
                    <x-dropdown-link href="/profile">
                        <i class="fas fa-user mr-2"></i>Profil
                    </x-dropdown-link>
                    <x-dropdown-link href="/settings">
                        <i class="fas fa-cog mr-2"></i>Pengaturan
                    </x-dropdown-link>
                    <div class="border-t border-gray-200 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
            <p class="text-[10px] text-gray-400 font-medium tracking-wider uppercase hidden md:block">Overview v.2.4</p>
        </div>
    </div>

    <!-- Sisi Kanan: Notifikasi & Profil -->
    <div class="flex items-center gap-2 md:gap-5">
        <button class="relative p-2.5 text-gray-500 hover:bg-gray-100 rounded-xl transition-all group">
            <svg class="w-5 h-5 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h2v-6h-2v6zm0-8h2V7h-2v2z"></path>
            </svg>
            <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
        </button>

        <div class="h-8 w-[1px] bg-gray-200 mx-1 hidden sm:block"></div>

        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 p-1 pr-3 hover:bg-gray-50 rounded-2xl transition-all group">
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-violet-500 rounded-xl flex items-center justify-center shadow-blue-200 shadow-lg group-hover:rotate-3 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>

                    <div class="text-left hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800 leading-tight">{{ $user ? $user->name : 'John Doe' }}</p>
                        <p class="text-[11px] text-gray-500 font-medium">{{ $user ? $user->email : 'john.doe@example.com' }}</p>
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-400 hidden sm:block group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('dashboard')">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </x-dropdown-link>
                <x-dropdown-link :href="route('profile.edit')">
                    <i class="fas fa-user mr-2"></i>Profil
                </x-dropdown-link>
                <x-dropdown-link href="/settings">
                    <i class="fas fa-cog mr-2"></i>Pengaturan
                </x-dropdown-link>
                <div class="border-t border-gray-200 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
