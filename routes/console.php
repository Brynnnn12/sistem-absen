<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule untuk mengirim laporan absensi bulanan setiap 30 hari
Schedule::command('attendance:send-monthly-reports')
    ->monthlyOn(1, '00:00') // Jalankan setiap tanggal 1 pukul 00:00
    ->withoutOverlapping() // Mencegah overlapping
    ->runInBackground(); // Jalankan di background
