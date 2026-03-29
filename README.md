# Sistem Absensi Simple

> Sistem absensi karyawan berbasis web yang sederhana namun powerful dengan fitur check-in/check-out otomatis dan role-based access control.

## 🎯 TUJUAN SISTEM

Sistem absensi sederhana untuk mencatat kehadiran karyawan dengan fitur check-in dan check-out real-time, dirancang untuk memudahkan monitoring kehadiran dan produktivitas tim.

## 📊 STRUKTUR DATABASE (3 Tabel)

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│    employees    │────▶│   attendances   │     │     users       │
├─────────────────┤     ├─────────────────┤     ├─────────────────┤
│ id (PK)         │     │ id (PK)         │     │ id (PK)         │
│ nik (unique)    │     │ employee_id(FK) │     │ name            │
│ name            │     │ date            │     │ email           │
│ email (unique)  │     │ check_in        │     │ password        │
│ phone           │     │ check_out       │     │ employee_id(FK) │
│ is_active       │     │ status          │     └─────────────────┘
└─────────────────┘     └─────────────────┘
```

**Relasi Database:**
- `employees (1) → attendances (M)`: Satu karyawan dapat memiliki banyak catatan absensi
- `users (1) → employees (1)`: Satu akun user terhubung dengan satu data karyawan

## 👥 ROLE & PERMISSION SYSTEM

| Role     | Akses & Permissions |
|----------|-------------------|
| **Admin** | • Melihat semua data absensi karyawan<br>• Mengedit data absensi (jam masuk/keluar, status)<br>• Mengelola data karyawan<br>• Melihat laporan dan statistik keseluruhan<br>• Filter dan export data absensi |
| **Employee** | • Check-in dan check-out mandiri<br>• Melihat riwayat absensi pribadi<br>• Melihat statistik kehadiran bulanan<br>• Update profil karyawan |

## 🔄 ALUR BISNIS SISTEM

### 1. Authentication & Authorization
```
User Login → Validasi Credentials → Cek Role → Redirect ke Dashboard Sesuai Role
```

### 2. Check-In Process (Absen Masuk)
```
Karyawan klik "Check In"
    ↓
Validasi: Belum absen hari ini?
    ↓
Jika Valid → Catat timestamp check-in
    ↓
Status Otomatis:
• ≤ 08:00 → "Present" (Tepat Waktu)
• > 08:00 → "Late" (Terlambat)
```

### 3. Check-Out Process (Absen Pulang)
```
Karyawan klik "Check Out"
    ↓
Validasi: Sudah check-in hari ini?
    ↓
Jika Valid → Catat timestamp check-out
    ↓
Hitung total jam kerja otomatis
```

### 4. Reporting & Analytics
```
Admin → Dashboard dengan metrics keseluruhan
Employee → Dashboard dengan metrics pribadi
```

## 📱 FITUR UTAMA

### 👨‍💼 Dashboard Administrator:
- 📊 **Real-time Statistics**: Total karyawan aktif, kehadiran harian, absensi bulanan
- 📋 **Attendance Management**: View, edit, filter riwayat absensi semua karyawan
- 👥 **Employee Management**: Daftar karyawan dengan status aktif/non-aktif
- 📈 **Advanced Reporting**: Filter berdasarkan tanggal, karyawan, status
- 📤 **Data Export**: Export laporan ke Excel untuk analisis lanjutan

### 👤 Dashboard Karyawan:
- 🕐 **Quick Attendance**: Check-in/check-out dengan satu klik
- 📊 **Personal Statistics**: Ringkasan kehadiran bulan berjalan
- 📅 **Attendance History**: Riwayat absensi 30 hari terakhir
- 👤 **Profile Management**: Update informasi pribadi

## 🗂️ ARSITEKTUR APLIKASI

```
app/
├── Models/
│   ├── User.php              # User model with Spatie Permission
│   ├── Employee.php          # Employee data model
│   └── Attendance.php        # Attendance records model
├── Http/Controllers/
│   ├── DashboardController.php    # Main dashboard logic
│   ├── AttendanceController.php   # Attendance CRUD operations
│   ├── EmployeeController.php     # Employee management
│   └── Auth/                      # Authentication controllers
├── Policies/                      # Authorization policies
│   ├── AttendancePolicy.php
│   └── EmployeePolicy.php
└── database/
    ├── migrations/               # Database schema migrations
    └── seeders/                 # Database seeding files

resources/
└── views/
    ├── layouts/
    │   └── app.blade.php        # Main application layout
    ├── dashboard/
    │   ├── admin.blade.php      # Admin dashboard
    │   └── employee.blade.php   # Employee dashboard
    ├── attendances/             # Attendance management views
    └── employees/               # Employee management views
```

## 🔧 TEKNOLOGI & DEPENDENCIES

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| **Framework** | Laravel | 13.x |
| **Authentication** | Laravel Breeze | Latest |
| **Authorization** | Spatie Laravel Permission | ^6.0 |
| **Database** | MySQL | 8.0+ |
| **Frontend Framework** | Tailwind CSS | ^3.4 |
| **UI Components** | Bootstrap | 5.3+ |
| **Icons** | Font Awesome | 6.x |
| **Export Library** | Laravel Excel | ^3.1 |
| **PHP** | PHP | 8.3+ |

### Package Dependencies:
```json
{
    "laravel/framework": "^13.0",
    "laravel/breeze": "*",
    "spatie/laravel-permission": "^6.0",
    "maatwebsite/excel": "^3.1",
    "tailwindcss": "^3.4"
}
```

## 📝 FITUR OTOMATIS & VALIDASI

### 🤖 Status Determination Logic:
- **Check-in ≤ 08:00**: Status `present` (tepat waktu)
- **Check-in > 08:00**: Status `late` (terlambat)
- **Working Hours**: Auto-calculated dari check-in ke check-out

### ✅ Business Rules Validation:
- ⛔ **Duplicate Check-in**: Tidak dapat check-in 2x dalam sehari
- ⛔ **Invalid Check-out**: Tidak dapat check-out tanpa check-in terlebih dahulu
- ⛔ **Duplicate Check-out**: Tidak dapat check-out 2x dalam sehari
- ⛔ **Future Dates**: Tidak dapat input absensi untuk tanggal mendatang

## 🚀 INSTALASI & SETUP

### Prerequisites:
- PHP 8.3 atau lebih tinggi
- Composer
- MySQL 8.0+
- Node.js & NPM (untuk asset compilation)

### Installation Steps:
```bash
# 1. Clone repository
git clone <repository-url>
cd sistem-absensi-simple

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Environment configuration
cp .env.example .env
php artisan key:generate

# 5. Database setup
# Configure database credentials in .env file

# 6. Run migrations & seeders
php artisan migrate
php artisan db:seed

# 7. Build assets
npm run build

# 8. Start development server
php artisan serve
```

## 🔑 AKUN DEFAULT

| Role | Email | Password | Deskripsi |
|------|-------|----------|-----------|
| **Administrator** | `admin@admin.com` | `password` | Full system access |
| **Employee** | `budi@email.com` | `password` | Regular employee account |
| **Employee** | `siti@email.com` | `password` | Regular employee account |

## 📊 DASHBOARD METRICS

### 📈 Administrator Dashboard:
- 👥 **Total Active Employees**: Jumlah karyawan aktif saat ini
- ✅ **Present Today**: Karyawan yang hadir hari ini
- ❌ **Absent Today**: Karyawan yang tidak hadir hari ini
- 📅 **Monthly Attendance**: Total kehadiran bulan berjalan
- 🕐 **Recent Activities**: 10 aktivitas absensi terbaru
- 📊 **Attendance Trends**: Grafik kehadiran 30 hari terakhir

### 👤 Employee Dashboard:
- 🟢 **Today's Status**: Status absen hari ini (Present/Late/Absent)
- 📊 **Monthly Summary**: Total hadir/absen bulan ini
- ⏰ **Working Hours**: Total jam kerja bulan berjalan
- 📅 **Recent Attendance**: 10 riwayat absensi terakhir
- 📈 **Attendance Rate**: Persentase kehadiran bulan ini

## 💡 KELEBIHAN SISTEM

- ✅ **Simple & Clean Architecture**: Hanya 3 tabel inti, mudah dipahami dan di-maintain
- ✅ **Production Ready**: Validasi bisnis yang ketat dan error handling yang proper
- ✅ **Role-Based Security**: Implementasi permission yang granular dengan Spatie
- ✅ **Responsive Design**: UI yang responsive dan mobile-friendly
- ✅ **Extensible**: Arsitektur yang mudah dikembangkan untuk fitur tambahan
- ✅ **Performance Optimized**: Query yang efisien dan caching yang tepat
- ✅ **Export Capabilities**: Fitur export Excel untuk reporting dan analisis

## 🎯 KESIMPULAN

**Sistem Absensi Simple** adalah solusi MVP (Minimum Viable Product) yang comprehensive untuk manajemen kehadiran karyawan, dibangun dengan teknologi modern Laravel 13. Sistem ini mencakup semua fitur essential untuk operasional HR sehari-hari dengan foundation yang solid untuk pengembangan lebih lanjut.

### Fitur Core yang Sudah Terimplementasi:
- ✅ Authentication & Authorization
- ✅ Real-time Check-in/Check-out
- ✅ Automated Status Determination
- ✅ Role-based Dashboard
- ✅ Attendance Reporting
- ✅ Employee Management
- ✅ Data Export Capabilities

### Potensi Pengembangan Lanjutan:
- 🔔 **Notification System**: Email/SMS reminders
- 📱 **Mobile App**: Companion mobile application
- 🗓️ **Leave Management**: Cuti, izin, dan lembur
- 📊 **Advanced Analytics**: Business intelligence dashboard
- 🤖 **Integration**: ERP, payroll system integration
- 📤 **Multi-format Export**: PDF reports, CSV, etc.

---

## 👨‍💻 Author

**brynnnn12**

*Full-Stack Developer specializing in Laravel applications and business process automation.*

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.
