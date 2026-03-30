<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController; // Contoh controller tambahan
use App\Http\Controllers\AttendanceController; // Tambahkan ini
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Bungkus semua yang butuh login ke dalam middleware 'auth'
Route::middleware(['auth', 'verified'])->group(function () {

    // Halaman Dashboard Utama
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Grouping khusus Dashboard (Profile, dll)
    Route::prefix('dashboard')->group(function () {

        // Route Profile (Breeze/Jetstream Default)
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
        });

        // --- CONTOH PEMBATASAN ROLE ---

        // Khusus Admin: Bisa kelola data Employee
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('employees', EmployeeController::class);
        });

        // Route attendances untuk admin dan karyawan
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('attendances', AttendanceController::class)->except(['index']);
        });
        Route::middleware(['role:karyawan'])->group(function () {
            Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
            Route::post('/attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.checkIn');
            Route::post('/attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.checkOut');
        });
    });
});

require __DIR__.'/auth.php';
