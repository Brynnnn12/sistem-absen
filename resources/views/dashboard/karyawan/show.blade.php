<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Profil Karyawan</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">NIK (Nomor Induk Karyawan)</span>
                                <span class="block text-lg text-gray-900 font-semibold">{{ $employee->nik }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Nama Lengkap</span>
                                <span class="block text-lg text-gray-900">{{ $employee->name }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Email Login</span>
                                <span class="block text-lg text-gray-900">{{ $employee->user?->email ?? '-' }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">User Akses (Sistem)</span>
                                <div class="mt-1">
                                    @if($employee->user)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Role: {{ ucfirst($employee->user->roles->first()?->name ?? 'Belum ada role') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Akun User Tidak Ditemukan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Nomor Telepon/HP</span>
                                <span class="block text-lg text-gray-900">{{ $employee->phone ?? '-' }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Status Aktif</span>
                                <div class="mt-1">
                                    @if($employee->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Non-Aktif</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Alamat Lengkap</span>
                                <p class="block text-base text-gray-900 mt-1 bg-gray-50 p-3 rounded-md min-h-[80px]">
                                    {{ $employee->address ?? 'Belum ada alamat' }}
                                </p>
                            </div>

                            <div class="mt-6 border-t border-gray-100 pt-4">
                                <span class="block text-xs text-gray-400">Dibuat pada: {{ $employee->created_at->format('d M Y, H:i') }}</span>
                                <span class="block text-xs text-gray-400">Terakhir diupdate: {{ $employee->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
