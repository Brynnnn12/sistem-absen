<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- NIK (Readonly/Disabled generally for existing, but based on your requirement. Let's make it editable with validation) -->
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                                <input type="text" name="nik" id="nik" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('nik', $employee->nik) }}" required>
                                @error('nik') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                                <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('name', $employee->name) }}" required>
                                @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('email', $employee->user->email) }}" required>
                                @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('phone', $employee->phone) }}" required>
                                @error('phone') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Password (Optional) -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru (Biarkan kosong jika tidak diubah)</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Konfirmasi Password (Optional) -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                                <select name="role_id" id="role_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    @php
                                        $currentUserRoleId = $employee->user ? $employee->user->roles->first()?->id : '';
                                    @endphp
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $id => $name)
                                        <option value="{{ $id }}" {{ (old('role_id') ?? $currentUserRoleId) == $id ? 'selected' : '' }}>{{ ucfirst($name) }}</option>
                                    @endforeach
                                </select>
                                @error('role_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>

                         <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('address', $employee->address) }}</textarea>
                                @error('address') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700">Status Aktif</label>
                                <div class="mt-2 flex items-center">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ (old('is_active', $employee->is_active) ?? $employee->is_active) ? 'checked' : '' }}>
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Tandai sebagai aktif</label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Jika tidak aktif, karyawan tidak bisa login.</p>
                                @error('is_active') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                         </div>


                        <div class="flex items-center justify-end">
                            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
