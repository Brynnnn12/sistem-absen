<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Monitoring Sistem</h3>
                        <div class="flex space-x-2">
                            <button onclick="runQueueWorker()" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <i class="fas fa-play mr-1"></i> Run Queue Worker
                            </button>
                            <button onclick="sendTestReport()" class="px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                                <i class="fas fa-envelope mr-1"></i> Test Report
                            </button>
                        </div>
                    </div>

                    <!-- System Status Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-blue-600 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-blue-600">Queue Status</div>
                                    <div class="text-2xl font-bold text-blue-900">{{ $pendingJobs }} Pending</div>
                                    <div class="text-sm text-blue-600">{{ $failedJobs }} Failed</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar-alt text-green-600 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-green-600">Scheduler Status</div>
                                    <div class="text-lg font-bold text-green-900">
                                        {{ $schedulerStatus['is_active'] ? 'Active' : 'Inactive' }}
                                    </div>
                                    <div class="text-sm text-green-600">
                                        Next: {{ $schedulerStatus['next_run']->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-server text-purple-600 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-purple-600">System Health</div>
                                    <div class="text-2xl font-bold text-purple-900">Healthy</div>
                                    <div class="text-sm text-purple-600">All systems operational</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Queue Management -->
                    <div class="mb-6">
                        <h4 class="text-md font-semibold mb-4">Queue Management</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h5 class="font-medium mb-2">Pending Jobs: {{ $pendingJobs }}</h5>
                                    <p class="text-sm text-gray-600 mb-3">Jobs waiting to be processed</p>
                                    <button onclick="clearPendingJobs()" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                        Clear All
                                    </button>
                                </div>
                                <div>
                                    <h5 class="font-medium mb-2">Failed Jobs: {{ $failedJobs }}</h5>
                                    <p class="text-sm text-gray-600 mb-3">Jobs that failed to process</p>
                                    <button onclick="retryFailedJobs()" class="px-3 py-1 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700 mr-2">
                                        Retry All
                                    </button>
                                    <button onclick="clearFailedJobs()" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                        Clear All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Failed Jobs -->
                    @if($recentFailedJobs->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-md font-semibold mb-4">Recent Failed Jobs</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queue</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Failed At</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exception</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($recentFailedJobs as $failedJob)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $failedJob->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $failedJob->queue }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($failedJob->failed_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            {{ Str::limit($failedJob->exception, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="retryJob({{ $failedJob->id }})" class="text-blue-600 hover:text-blue-900 mr-2">
                                                <i class="fas fa-redo"></i> Retry
                                            </button>
                                            <button onclick="deleteFailedJob({{ $failedJob->id }})" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Manual Commands -->
                    <div class="mb-6">
                        <h4 class="text-md font-semibold mb-4">Manual Commands</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-medium mb-2">Send Monthly Reports</h5>
                                <p class="text-sm text-gray-600 mb-3">Kirim laporan absensi bulanan ke semua karyawan</p>
                                <button onclick="sendMonthlyReports()" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                    <i class="fas fa-paper-plane mr-1"></i> Send Now
                                </button>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-medium mb-2">Test Email</h5>
                                <p class="text-sm text-gray-600 mb-3">Kirim email test ke alamat Anda</p>
                                <button onclick="sendTestEmail()" class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                    <i class="fas fa-envelope mr-1"></i> Send Test
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-md font-semibold mb-4">System Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <strong>Laravel Version:</strong> {{ app()->version() }}<br>
                                <strong>PHP Version:</strong> {{ PHP_VERSION }}<br>
                                <strong>Queue Driver:</strong> {{ config('queue.default') }}<br>
                                <strong>Mail Driver:</strong> {{ config('mail.default') }}
                            </div>
                            <div>
                                <strong>Database:</strong> {{ config('database.default') }}<br>
                                <strong>Cache Driver:</strong> {{ config('cache.default') }}<br>
                                <strong>Session Driver:</strong> {{ config('session.driver') }}<br>
                                <strong>Timezone:</strong> {{ config('app.timezone') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function runQueueWorker() {
            if (confirm('Apakah Anda yakin ingin menjalankan queue worker?')) {
                fetch('/admin/run-queue-worker', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || 'Queue worker started');
                    location.reload();
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }

        function sendTestReport() {
            if (confirm('Kirim laporan test ke email Anda?')) {
                fetch('/admin/send-test-report', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || 'Test report sent');
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }

        function sendMonthlyReports() {
            if (confirm('Kirim laporan bulanan ke semua karyawan?')) {
                fetch('/admin/send-monthly-reports', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || 'Monthly reports sent');
                    location.reload();
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }

        function clearPendingJobs() {
            if (confirm('Hapus semua pending jobs?')) {
                alert('Fitur ini belum diimplementasikan');
            }
        }

        function retryFailedJobs() {
            if (confirm('Retry semua failed jobs?')) {
                alert('Fitur ini belum diimplementasikan');
            }
        }

        function clearFailedJobs() {
            if (confirm('Hapus semua failed jobs?')) {
                alert('Fitur ini belum diimplementasikan');
            }
        }

        function retryJob(jobId) {
            if (confirm('Retry job ' + jobId + '?')) {
                alert('Fitur ini belum diimplementasikan');
            }
        }

        function deleteFailedJob(jobId) {
            if (confirm('Hapus failed job ' + jobId + '?')) {
                alert('Fitur ini belum diimplementasikan');
            }
        }

        function sendTestEmail() {
            if (confirm('Kirim email test?')) {
                alert('Fitur ini belum diimplementasikan');
            }
        }
    </script>
</x-app-layout>
