<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Laporan Absensi</h3>
                        <div class="flex space-x-2">
                            <button onclick="window.print()" class="px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                                <i class="fas fa-print mr-1"></i> Print
                            </button>
                            <button onclick="exportToPDF()" class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                                <i class="fas fa-file-pdf mr-1"></i> Export PDF
                            </button>
                            <button onclick="exportToExcel()" class="px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                                <i class="fas fa-file-excel mr-1"></i> Export Excel
                            </button>
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('reports.attendance') }}" class="flex flex-wrap gap-4 items-end">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700">Karyawan (Opsional)</label>
                                <select id="employee_id" name="employee_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Semua Karyawan</option>
                                    @foreach(\App\Models\Employee::where('is_active', true)->orderBy('name')->get() as $employee)
                                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }} - {{ $employee->nik }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                    <i class="fas fa-search mr-1"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-blue-600">Total Karyawan</div>
                                    <div class="text-2xl font-bold text-blue-900">{{ count($reportData) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-green-600">Rata-rata Present</div>
                                    <div class="text-2xl font-bold text-green-900">
                                        {{ count($reportData) > 0 ? round(collect($reportData)->avg('present_percentage'), 1) : 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-yellow-600">Rata-rata Late</div>
                                    <div class="text-2xl font-bold text-yellow-900">
                                        {{ count($reportData) > 0 ? round(collect($reportData)->avg('late_percentage'), 1) : 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-red-600">Rata-rata Absent</div>
                                    <div class="text-2xl font-bold text-red-900">
                                        {{ count($reportData) > 0 ? round(collect($reportData)->avg('absent_percentage'), 1) : 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200" id="reportTable">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Hari</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terlambat</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absen</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Hadir</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Terlambat</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Absen</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($reportData as $report)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $report['employee']->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report['employee']->nik }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report['total_days'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report['present_count'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report['late_count'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report['absent_count'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $report['present_percentage'] >= 80 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $report['present_percentage'] }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $report['late_percentage'] }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $report['absent_percentage'] }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="showEmployeeDetail({{ $report['employee']->id }})"
                                                class="text-blue-600 hover:text-blue-900 mr-2" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Tidak ada data karyawan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Period Info -->
                    <div class="mt-6 text-sm text-gray-600">
                        <p><strong>Periode Laporan:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                        <p><strong>Dibuat pada:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function exportToPDF() {
            // Get current filter values
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const employeeId = document.getElementById('employee_id').value;

            // Build URL with parameters
            let url = '/dashboard/reports/attendance/pdf';
            const params = new URLSearchParams();

            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (employeeId) params.append('employee_id', employeeId);

            if (params.toString()) {
                url += '?' + params.toString();
            }

            // Open PDF in new tab/window
            window.open(url, '_blank');
        }

        function exportToExcel() {
            // Simple CSV export
            const table = document.getElementById('reportTable');
            let csv = [];

            // Get headers
            const headers = [];
            for (let i = 0; i < table.rows[0].cells.length - 1; i++) { // Exclude last column (Aksi)
                headers.push(table.rows[0].cells[i].innerText);
            }
            csv.push(headers.join(','));

            // Get data rows
            for (let i = 1; i < table.rows.length; i++) {
                if (table.rows[i].cells.length > 1) { // Skip empty rows
                    const row = [];
                    for (let j = 0; j < table.rows[i].cells.length - 1; j++) { // Exclude last column (Aksi)
                        row.push(table.rows[i].cells[j].innerText.replace(/,/g, ';')); // Replace commas
                    }
                    csv.push(row.join(','));
                }
            }

            // Download CSV
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'laporan-absensi-{{ $startDate }}-{{ $endDate }}.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function showEmployeeDetail(employeeId) {
            // Find employee data
            const employeeData = @json($reportData).find(report => report.employee.id === employeeId);
            if (!employeeData) return;

            // Create modal content
            const modalContent = `
                <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="employee-modal">
                    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Detail Absensi - ${employeeData.employee.name}</h3>
                            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-blue-600">${employeeData.present_count}</div>
                                <div class="text-sm text-blue-600">Hadir</div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-yellow-600">${employeeData.late_count}</div>
                                <div class="text-sm text-yellow-600">Terlambat</div>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-red-600">${employeeData.absent_count}</div>
                                <div class="text-sm text-red-600">Absen</div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-2 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                        <th class="px-4 py-2 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                        <th class="px-4 py-2 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${employeeData.attendances.map(attendance => `
                                        <tr>
                                            <td class="px-4 py-2 border-b border-gray-200 text-sm">${new Date(attendance.date).toLocaleDateString('id-ID')}</td>
                                            <td class="px-4 py-2 border-b border-gray-200 text-sm">${attendance.check_in || '-'}</td>
                                            <td class="px-4 py-2 border-b border-gray-200 text-sm">${attendance.check_out || '-'}</td>
                                            <td class="px-4 py-2 border-b border-gray-200 text-sm">
                                                ${attendance.status === 'present'
                                                    ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>'
                                                    : attendance.status === 'late'
                                                    ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Terlambat</span>'
                                                    : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Absen</span>'}
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalContent);
        }

        function closeModal() {
            const modal = document.getElementById('employee-modal');
            if (modal) {
                modal.remove();
            }
        }
    </script>
</x-app-layout>
