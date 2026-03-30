<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi - {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #007bff;
            margin: 0 0 5px 0;
            font-size: 18px;
        }

        .header p {
            color: #666;
            margin: 0;
            font-size: 11px;
        }

        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 10px;
        }

        .summary-item {
            display: table-cell;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            width: 25%;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .summary-label {
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .present { color: #28a745; }
        .late { color: #ffc107; }
        .absent { color: #dc3545; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        th, td {
            padding: 6px 4px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
        }

        .status-present {
            background-color: #d4edda;
            color: #155724;
        }

        .status-late {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-absent {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            text-align: center;
            color: #666;
            font-size: 9px;
            border-top: 1px solid #e9ecef;
            padding-top: 10px;
            margin-top: 20px;
        }

        .employee-section {
            page-break-inside: avoid;
            margin-bottom: 15px;
        }

        .employee-header {
            background-color: #e9ecef;
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 3px;
        }

        .employee-name {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .employee-stats {
            display: table;
            width: 100%;
            font-size: 9px;
        }

        .employee-stat {
            display: table-cell;
            text-align: center;
            padding: 2px;
        }

        @media print {
            body { print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>📊 Laporan Absensi Karyawan</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
        <p>Dibuat pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>

    <!-- Summary -->
    <div class="summary-grid">
        <div class="summary-item">
            <div class="summary-value present">{{ count($reportData) }}</div>
            <div class="summary-label">Total Karyawan</div>
        </div>
        <div class="summary-item">
            <div class="summary-value present">{{ count($reportData) > 0 ? round(collect($reportData)->avg('present_percentage'), 1) : 0 }}%</div>
            <div class="summary-label">Rata-rata Hadir</div>
        </div>
        <div class="summary-item">
            <div class="summary-value late">{{ count($reportData) > 0 ? round(collect($reportData)->avg('late_percentage'), 1) : 0 }}%</div>
            <div class="summary-label">Rata-rata Terlambat</div>
        </div>
        <div class="summary-item">
            <div class="summary-value absent">{{ count($reportData) > 0 ? round(collect($reportData)->avg('absent_percentage'), 1) : 0 }}%</div>
            <div class="summary-label">Rata-rata Absen</div>
        </div>
    </div>

    <!-- Detailed Report Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Karyawan</th>
                <th style="width: 10%;">NIK</th>
                <th style="width: 8%;">Total Hari</th>
                <th style="width: 8%;">Hadir</th>
                <th style="width: 8%;">Terlambat</th>
                <th style="width: 8%;">Absen</th>
                <th style="width: 10%;">% Hadir</th>
                <th style="width: 10%;">% Terlambat</th>
                <th style="width: 10%;">% Absen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $report)
            <tr>
                <td style="font-weight: 500;">{{ $report['employee']->name }}</td>
                <td>{{ $report['employee']->nik }}</td>
                <td style="text-align: center;">{{ $report['total_days'] }}</td>
                <td style="text-align: center;">{{ $report['present_count'] }}</td>
                <td style="text-align: center;">{{ $report['late_count'] }}</td>
                <td style="text-align: center;">{{ $report['absent_count'] }}</td>
                <td style="text-align: center;">
                    <span class="status-badge status-present">{{ $report['present_percentage'] }}%</span>
                </td>
                <td style="text-align: center;">
                    <span class="status-badge status-late">{{ $report['late_percentage'] }}%</span>
                </td>
                <td style="text-align: center;">
                    <span class="status-badge status-absent">{{ $report['absent_percentage'] }}%</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px; color: #666;">
                    Tidak ada data karyawan dalam periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Individual Employee Details -->
    @if($reportData->count() > 0)
    <h2 style="font-size: 14px; margin: 20px 0 10px 0; color: #007bff; border-bottom: 1px solid #007bff; padding-bottom: 5px;">
        Detail Per Karyawan
    </h2>

    @foreach($reportData as $report)
    <div class="employee-section">
        <div class="employee-header">
            <div class="employee-name">{{ $report['employee']->name }} ({{ $report['employee']->nik }})</div>
            <div class="employee-stats">
                <div class="employee-stat">Hadir: {{ $report['present_count'] }} ({{ $report['present_percentage'] }}%)</div>
                <div class="employee-stat">Terlambat: {{ $report['late_count'] }} ({{ $report['late_percentage'] }}%)</div>
                <div class="employee-stat">Absen: {{ $report['absent_count'] }} ({{ $report['absent_percentage'] }}%)</div>
            </div>
        </div>

        @if($report['attendances']->count() > 0)
        <table style="font-size: 9px;">
            <thead>
                <tr>
                    <th style="width: 20%;">Tanggal</th>
                    <th style="width: 15%;">Check In</th>
                    <th style="width: 15%;">Check Out</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 35%;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report['attendances'] as $attendance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                    <td>{{ $attendance->check_in ?: '-' }}</td>
                    <td>{{ $attendance->check_out ?: '-' }}</td>
                    <td>
                        @if($attendance->status == 'present')
                            <span class="status-badge status-present">Hadir</span>
                        @elseif($attendance->status == 'late')
                            <span class="status-badge status-late">Terlambat</span>
                        @else
                            <span class="status-badge status-absent">Absen</span>
                        @endif
                    </td>
                    <td>
                        @if($attendance->status == 'late')
                            Terlambat check-in
                        @elseif(!$attendance->check_in)
                            Tidak check-in
                        @elseif(!$attendance->check_out)
                            Tidak check-out
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="font-size: 10px; color: #666; font-style: italic; padding: 10px;">
            Tidak ada data absensi dalam periode ini.
        </p>
        @endif
    </div>

    @if(!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif
    @endforeach
    @endif

    <div class="footer">
        <p><strong>Sistem Absensi Otomatis</strong></p>
        <p>Laporan ini dibuat secara otomatis pada {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total Karyawan: {{ count($reportData) }} | Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

</body>
</html>
