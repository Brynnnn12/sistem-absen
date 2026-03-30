<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Detail Absensi</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('attendances.index') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <a href="{{ route('attendances.edit', $attendance) }}" class="px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Karyawan</span>
                                <span class="block text-lg text-gray-900 font-semibold">{{ $attendance->employee->name }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">NIK</span>
                                <span class="block text-lg text-gray-900">{{ $attendance->employee->nik }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Tanggal</span>
                                <span class="block text-lg text-gray-900">{{ $attendance->date->format('d/m/Y') }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Status</span>
                                <div class="mt-1">
                                    @if($attendance->status == 'present')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                                    @elseif($attendance->status == 'late')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Late</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Absent</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Check In</span>
                                <span class="block text-lg text-gray-900">{{ $attendance->check_in ?: '-' }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Check Out</span>
                                <span class="block text-lg text-gray-900">{{ $attendance->check_out ?: '-' }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Durasi Kerja</span>
                                <span class="block text-lg text-gray-900">
                                    @if($attendance->check_in && $attendance->check_out)
                                        {{ \Carbon\Carbon::parse($attendance->check_in)->diff(\Carbon\Carbon::parse($attendance->check_out))->format('%H jam %I menit') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>

                            <div class="mt-6 border-t border-gray-100 pt-4">
                                <span class="block text-xs text-gray-400">Dibuat pada: {{ $attendance->created_at->format('d M Y, H:i') }}</span>
                                <span class="block text-xs text-gray-400">Terakhir diupdate: {{ $attendance->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
