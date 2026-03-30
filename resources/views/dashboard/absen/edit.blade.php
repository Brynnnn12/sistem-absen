<x-app-layout>
    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('attendances.update', $attendance) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Employee -->
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700">Karyawan</label>
                                <input type="text" list="employees" name="employee_id" id="employee_id" value="{{ $attendance->employee->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Cari karyawan..." required>
                                <datalist id="employees">
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->nik }})</option>
                                    @endforeach
                                </datalist>
                                @error('employee_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Date -->
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" name="date" id="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" required>
                                @error('date') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Check In -->
                            <div>
                                <label for="check_in" class="block text-sm font-medium text-gray-700">Check In</label>
                                <input type="time" name="check_in" id="check_in" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('check_in', $attendance->check_in ? $attendance->check_in->format('H:i') : '') }}">
                                @error('check_in') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Check Out -->
                            <div>
                                <label for="check_out" class="block text-sm font-medium text-gray-700">Check Out</label>
                                <input type="time" name="check_out" id="check_out" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('check_out', $attendance->check_out ? $attendance->check_out->format('H:i') : '') }}">
                                @error('check_out') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Pilih Status</option>
                                    <option value="present" {{ (old('status') ?? $attendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="late" {{ (old('status') ?? $attendance->status) == 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="absent" {{ (old('status') ?? $attendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                                </select>
                                @error('status') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('attendances.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
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
