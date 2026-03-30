<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            @if(auth()->user()->hasRole('admin'))
                <!-- Dashboard Administrator -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Administrator</h1>
                    <p class="text-gray-600">Kelola sistem absensi dan data karyawan</p>
                </div>

                <!-- Statistics Cards -->
                @php
                    $totalEmployees = \App\Models\Employee::where('is_active', true)->count();
                    $todayAttendances = \App\Models\Attendance::where('date', now()->toDateString())->count();
                    $presentToday = \App\Models\Attendance::where('date', now()->toDateString())->where('status', 'present')->count();
                    $lateToday = \App\Models\Attendance::where('date', now()->toDateString())->where('status', 'late')->count();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Karyawan</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalEmployees }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100">
                                <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Absensi Hari Ini</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $todayAttendances }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-emerald-100">
                                <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Tepat Waktu</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $presentToday }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Terlambat</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $lateToday }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold mb-2">Kelola Karyawan</h3>
                                <p class="text-blue-100 mb-4">Tambah, edit, dan kelola data karyawan</p>
                                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                                    <i class="fas fa-arrow-right mr-2"></i>
                                    Kelola Karyawan
                                </a>
                            </div>
                            <i class="fas fa-user-friends text-4xl text-blue-200"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold mb-2">Kelola Absensi</h3>
                                <p class="text-green-100 mb-4">Pantau dan kelola data absensi karyawan</p>
                                <a href="{{ route('attendances.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-green-600 rounded-lg hover:bg-green-50 transition-colors font-medium">
                                    <i class="fas fa-arrow-right mr-2"></i>
                                    Kelola Absensi
                                </a>
                            </div>
                            <i class="fas fa-calendar-alt text-4xl text-green-200"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold mb-2">Laporan & Statistik</h3>
                                <p class="text-purple-100 mb-4">Lihat laporan lengkap dan analisis</p>
                                <a href="#" class="inline-flex items-center px-4 py-2 bg-white text-purple-600 rounded-lg hover:bg-purple-50 transition-colors font-medium">
                                    <i class="fas fa-arrow-right mr-2"></i>
                                    Lihat Laporan
                                </a>
                            </div>
                            <i class="fas fa-chart-bar text-4xl text-purple-200"></i>
                        </div>
                    </div>
                </div>

            @else
                <!-- Dashboard Karyawan -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
                            <p class="text-gray-600">Pantau absensi dan produktivitas Anda hari ini</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ now()->format('l, d F Y') }}</p>
                                <p class="text-lg font-semibold text-gray-900">{{ now()->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-400 text-xl mr-3"></i>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Quick Attendance Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-2xl text-blue-600 mr-3"></i>
                            <h2 class="text-xl font-bold text-gray-900">Absensi Hari Ini</h2>
                        </div>
                    </div>

                    <div class="p-6">
                        @php
                            $today = now()->toDateString();
                            $currentTime = now()->format('H:i');
                            $isAfterCheckInCutoff = $currentTime > '07:00';
                            $isCheckOutTime = $currentTime >= '15:00' && $currentTime <= '16:00';
                            $attendance = \App\Models\Attendance::where('employee_id', auth()->user()->employee?->id)->where('date', $today)->first();
                        @endphp

                        @if($isAfterCheckInCutoff && !$attendance)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-red-800">Waktu Check-in Berakhir</h3>
                                        <p class="text-sm text-red-600 mt-1">Check-in maksimal pukul 07:00 pagi</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($attendance && !$attendance->check_out && !$isCheckOutTime)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-blue-800">Waktu Check-out</h3>
                                        <p class="text-sm text-blue-600 mt-1">Check-out hanya bisa dilakukan antara pukul 15:00 - 16:00</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            @if(!$attendance)
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Check-in Hari Ini</h3>
                                    <p class="text-gray-600">Pastikan Anda check-in sebelum pukul 07:00</p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if(!$isAfterCheckInCutoff)
                                        <form action="{{ route('attendances.checkIn') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 transition-colors font-medium shadow-sm">
                                                <i class="fas fa-sign-in-alt mr-2"></i>
                                                Check In
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="inline-flex items-center px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium shadow-sm">
                                            <i class="fas fa-sign-in-alt mr-2"></i>
                                            Check In (Waktu Habis)
                                        </button>
                                    @endif
                                </div>
                            @elseif(!$attendance->check_out)
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Sudah Check-in</h3>
                                    <div class="flex items-center space-x-4 text-sm">
                                        <span class="flex items-center text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Check In: <strong>{{ $attendance->check_in }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($isCheckOutTime)
                                        <form action="{{ route('attendances.checkOut') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition-colors font-medium shadow-sm">
                                                <i class="fas fa-sign-out-alt mr-2"></i>
                                                Check Out
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="inline-flex items-center px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium shadow-sm">
                                            <i class="fas fa-sign-out-alt mr-2"></i>
                                            Check Out (Belum Waktunya)
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Absensi Lengkap Hari Ini ✅</h3>
                                    <div class="flex items-center space-x-6 text-sm">
                                        <span class="flex items-center text-green-600">
                                            <i class="fas fa-sign-in-alt mr-1"></i>
                                            Check In: <strong>{{ $attendance->check_in }}</strong>
                                        </span>
                                        <span class="flex items-center text-red-600">
                                            <i class="fas fa-sign-out-alt mr-1"></i>
                                            Check Out: <strong>{{ $attendance->check_out }}</strong>
                                        </span>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $attendance->status == 'present' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Durasi Kerja</p>
                                        <p class="text-lg font-bold text-gray-900">
                                            @if($attendance->check_in && $attendance->check_out)
                                                {{ \Carbon\Carbon::parse($attendance->check_in)->diff(\Carbon\Carbon::parse($attendance->check_out))->format('%H:%I') }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Attendance History -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-history text-2xl text-gray-600 mr-3"></i>
                                <h2 class="text-xl font-bold text-gray-900">Riwayat Absensi</h2>
                            </div>
                            <span class="text-sm text-gray-500">30 hari terakhir</span>
                        </div>
                    </div>

                    <div class="p-6">
                        @php
                            $recentAttendances = \App\Models\Attendance::where('employee_id', auth()->user()->employee?->id)
                                ->where('date', '>=', now()->subDays(30)->toDateString())
                                ->orderBy('date', 'desc')
                                ->take(10)
                                ->get();
                        @endphp

                        @forelse($recentAttendances as $att)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-calendar-day text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $att->date->format('d M Y') }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $att->check_in ?: '-' }} - {{ $att->check_out ?: '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    @if($att->check_in && $att->check_out)
                                        <span class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($att->check_in)->diff(\Carbon\Carbon::parse($att->check_out))->format('%H:%I') }}
                                        </span>
                                    @endif
                                    @if($att->status == 'present')
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Tepat Waktu</span>
                                    @elseif($att->status == 'late')
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Terlambat</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak Hadir</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Absensi</h3>
                                <p class="text-gray-500">Riwayat absensi Anda akan muncul di sini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
