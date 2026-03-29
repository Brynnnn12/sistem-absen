@props(['isOpen' => true])

@php
// Check user role and set menu items accordingly
$user = auth()->user();
if ($user && $user->hasRole('karyawan')) {
    $menuItems = [
        ['icon' => 'fa-calendar-check', 'label' => 'Absen', 'path' => '/absen', 'active' => request()->is('absen*')],
    ];
} else {
    $menuItems = [
        ['icon' => 'fa-users', 'label' => 'Karyawan', 'path' => '/dashboard/karyawan', 'active' => request()->is('karyawan*')],
        ['icon' => 'fa-calendar-check', 'label' => 'Absen', 'path' => '/dashboard/absen', 'active' => request()->is('absen*')],
        ['icon' => 'fa-chart-bar', 'label' => 'Laporan', 'path' => '/dashboard/laporan', 'active' => request()->is('laporan*')],
    ];
}
@endphp

<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed md:relative z-50 w-64 h-full transition-transform duration-300 ease-in-out md:translate-x-0 bg-gradient-to-br from-blue-600 to-blue-800 shadow-xl flex flex-col">

    <div class="p-6 flex items-center gap-3 border-b border-blue-500/30">
        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center font-bold text-xl text-white shadow-lg">
            D
        </div>
        <span class="font-bold text-xl tracking-tight text-white">Dashboard</span>

        <button @click="sidebarOpen = false" class="ml-auto md:hidden p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition-all">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        @foreach($menuItems as $item)
        <a
            href="{{ $item['path'] }}"
            class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 font-medium relative
            {{ $item['active']
                ? 'bg-white/20 text-white shadow-lg backdrop-blur-sm'
                : 'text-white/80 hover:bg-white/10 hover:text-white hover:shadow-md' }}"
        >
            @if($item['active'])
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-white rounded-r-full"></div>
            @endif
            <i class="fas {{ $item['icon'] }} group-hover:scale-110 transition-transform w-5 text-center"></i>
            <span>{{ $item['label'] }}</span>
        </a>
        @endforeach
    </nav>

    <div class="p-4 border-t border-blue-500/30">
        <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-3 flex items-center justify-center text-white/80 font-medium hover:bg-white/20 transition-colors cursor-pointer">
            <i class="fas fa-layer-group mr-2"></i> Parallel Space
        </div>
    </div>
</div>
