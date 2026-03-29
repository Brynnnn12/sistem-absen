<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController; // Contoh controller tambahan
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
            // Route::resource('employees', EmployeeController::class);
        });

        // Khusus Karyawan: Hanya bisa lihat data sendiri (misal)
        Route::middleware(['role:karyawan'])->group(function () {
            // Route::get('/my-status', [EmployeeController::class, 'showMyStatus'])->name('employee.status');
        });
    });
});

require __DIR__.'/auth.php';
