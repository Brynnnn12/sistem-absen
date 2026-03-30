# Sistem Absensi - Task Scheduling & Queue

## Fitur Laporan Absensi Bulanan Otomatis

Sistem ini memiliki fitur pengiriman laporan absensi bulanan secara otomatis ke semua karyawan setiap bulan.

### Cara Kerja

1. **Scheduler** menjalankan command `attendance:send-monthly-reports` setiap tanggal 1 pukul 00:00
2. **Command** membuat job `SendMonthlyAttendanceReport` dan mengirim ke queue
3. **Queue Worker** memproses job secara asynchronous
4. **Job** mengirim email laporan absensi ke setiap karyawan aktif

### Komponen yang Dibuat

#### 1. Job: `SendMonthlyAttendanceReport`
- Lokasi: `app/Jobs/SendMonthlyAttendanceReport.php`
- Fungsi: Mengirim laporan absensi bulanan ke semua karyawan
- Parameter: bulan dan tahun (default: bulan sebelumnya)

#### 2. Mailable: `MonthlyAttendanceReport`
- Lokasi: `app/Mail/MonthlyAttendanceReport.php`
- Template: `resources/views/emails/monthly-attendance-report.blade.php`
- Fungsi: Template email laporan absensi

#### 3. Command: `SendMonthlyAttendanceReports`
- Lokasi: `app/Console/Commands/SendMonthlyAttendanceReports.php`
- Signature: `attendance:send-monthly-reports`
- Opsi: `--month` dan `--year` untuk custom periode

#### 4. Scheduler
- Lokasi: `routes/console.php`
- Jadwal: Setiap tanggal 1 pukul 00:00
- Command: `attendance:send-monthly-reports`

### Konfigurasi Email

Pastikan konfigurasi email di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Cara Menjalankan

#### 1. Jalankan Queue Worker
```bash
php artisan queue:work
```

#### 2. Jalankan Scheduler (untuk development)
```bash
php artisan schedule:run
```

#### 3. Test Manual Pengiriman Laporan
```bash
# Kirim laporan bulan sebelumnya
php artisan attendance:send-monthly-reports

# Kirim laporan bulan dan tahun tertentu
php artisan attendance:send-monthly-reports --month=12 --year=2023
```

### Cron Job untuk Production

Tambahkan cron job di server:

```bash
# Jalankan scheduler setiap menit
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

# Jalankan queue worker (opsional, bisa menggunakan supervisor)
php artisan queue:work --sleep=3 --tries=3
```

### Monitoring

#### Log Files
- Cek log aplikasi di `storage/logs/laravel.log`
- Job akan mencatat proses pengiriman

#### Queue Monitoring
```bash
# Cek status queue
php artisan queue:status

# Cek failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Template Email

Email laporan berisi:
- ✅ Informasi karyawan (nama, NIK, email)
- ✅ Statistik absensi (hadir, terlambat, absen)
- ✅ Persentase kehadiran
- ✅ Status performa (Excellent/Good/Fair/Poor)
- ✅ Detail absensi harian (tanggal, check-in, check-out, status)
- ✅ Footer dengan informasi sistem

### Keamanan & Performance

- ✅ Job berjalan secara asynchronous (queue)
- ✅ Scheduler menggunakan `withoutOverlapping()` untuk mencegah duplikasi
- ✅ Error handling dengan logging
- ✅ Rate limiting untuk mencegah spam email

### Testing

#### Unit Test untuk Job
```bash
php artisan make:test SendMonthlyAttendanceReportTest
```

#### Manual Testing
1. Buat data absensi untuk testing
2. Jalankan command manual
3. Periksa log dan email yang dikirim
4. Verifikasi isi email sesuai dengan data

### Troubleshooting

#### Email tidak terkirim
1. Cek konfigurasi MAIL di `.env`
2. Cek log Laravel untuk error
3. Test dengan `php artisan tinker`:
```php
Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

#### Job tidak diproses
1. Pastikan queue worker berjalan: `php artisan queue:work`
2. Cek status queue: `php artisan queue:status`
3. Cek failed jobs: `php artisan queue:failed`

#### Scheduler tidak berjalan
1. Pastikan cron job sudah dikonfigurasi
2. Test manual: `php artisan schedule:run`
3. Cek log scheduler

### Customisasi

#### Mengubah Jadwal Pengiriman
Edit di `routes/console.php`:
```php
// Contoh: kirim setiap tanggal 15 pukul 09:00
Schedule::command('attendance:send-monthly-reports')
    ->monthlyOn(15, '09:00')
    ->withoutOverlapping()
    ->runInBackground();
```

#### Menambah Konten Email
Edit template di `resources/views/emails/monthly-attendance-report.blade.php`

#### Menambah Logic Job
Edit job di `app/Jobs/SendMonthlyAttendanceReport.php`
