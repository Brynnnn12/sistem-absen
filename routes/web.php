<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Middleware 'auth' untuk semua user yang sudah login
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('dashboard')->group(function () {

        // --- PROFILE ---
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
        });

        // --- KHUSUS ADMIN ---
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('employees', EmployeeController::class);
            Route::get('/reports/attendance', [AttendanceController::class, 'report'])->name('reports.attendance');
            Route::get('/reports/attendance/pdf', [AttendanceController::class, 'exportReportPDF'])->name('reports.attendance.pdf');
            Route::get('/system/monitoring', [AttendanceController::class, 'monitoring'])->name('system.monitoring');
        });

        // --- MIXED (ADMIN & KARYAWAN) ---
        // Gunakan middleware 'role:admin|karyawan' atau hapus middleware role di sini
        // karena filter data sudah dilakukan di Controller @index
        Route::middleware(['role:admin|karyawan'])->group(function () {

            // Resource ini mencakup index, create, store, edit, update, destroy, show
            Route::resource('attendances', AttendanceController::class);

            // Route tambahan untuk aksi Check-in/Out
            Route::post('/attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.checkIn');
            Route::post('/attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.checkOut');
        });

    });
});

require __DIR__.'/auth.php';
