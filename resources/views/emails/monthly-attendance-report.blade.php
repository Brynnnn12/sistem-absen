<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Bulanan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            margin: 20px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }
        .employee-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .present { color: #28a745; }
        .late { color: #ffc107; }
        .absent { color: #dc3545; }
        .performance-status {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 18px;
        }
        .excellent { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .good { background-color: #cce5ff; color: #004085; border: 1px solid #b3d7ff; }
        .fair { background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .poor { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .attendance-table th,
        .attendance-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        .attendance-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            margin-top: 30px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Laporan Absensi Bulanan</h1>
            <p>Periode: {{ $reportData['month'] }}</p>
        </div>

        <div class="employee-info">
            <h3 style="margin-top: 0; color: #007bff;">Informasi Karyawan</h3>
            <p><strong>Nama:</strong> {{ $reportData['employee']->name }}</p>
            <p><strong>NIK:</strong> {{ $reportData['employee']->nik }}</p>
            <p><strong>Email:</strong> {{ $reportData['employee']->user->email }}</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value present">{{ $reportData['present_count'] }}</div>
                <div class="stat-label">Hadir</div>
                <div style="font-size: 12px; color: #666; margin-top: 5px;">{{ $reportData['present_percentage'] }}%</div>
            </div>
            <div class="stat-card">
                <div class="stat-value late">{{ $reportData['late_count'] }}</div>
                <div class="stat-label">Terlambat</div>
                <div style="font-size: 12px; color: #666; margin-top: 5px;">{{ $reportData['late_percentage'] }}%</div>
            </div>
            <div class="stat-card">
                <div class="stat-value absent">{{ $reportData['absent_count'] }}</div>
                <div class="stat-label">Absen</div>
                <div style="font-size: 12px; color: #666; margin-top: 5px;">{{ $reportData['absent_percentage'] }}%</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $reportData['total_days'] }}</div>
                <div class="stat-label">Total Hari</div>
                <div style="font-size: 12px; color: #666; margin-top: 5px;">Bulan Ini</div>
            </div>
        </div>

        <div class="performance-status {{ $reportData['performance_status']['color'] }}">
            🎯 Status Performa: {{ $reportData['performance_status']['status'] }}<br>
            <small>{{ $reportData['performance_status']['message'] }}</small>
        </div>

        @if($reportData['attendances']->count() > 0)
        <h3 style="color: #007bff; margin-bottom: 15px;">Detail Absensi</h3>
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['attendances'] as $attendance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                    <td>{{ $attendance->check_in ?: '-' }}</td>
                    <td>{{ $attendance->check_out ?: '-' }}</td>
                    <td>
                        @if($attendance->status == 'present')
                            <span style="color: #28a745; font-weight: bold;">✓ Hadir</span>
                        @elseif($attendance->status == 'late')
                            <span style="color: #ffc107; font-weight: bold;">⚠ Terlambat</span>
                        @else
                            <span style="color: #dc3545; font-weight: bold;">✗ Absen</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="footer">
            <p><strong>Sistem Absensi Otomatis</strong></p>
            <p>Laporan ini dikirim secara otomatis setiap bulan</p>
            <p>Dibuat pada: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
