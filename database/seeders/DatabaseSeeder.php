<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Role Spatie
        $adminRole = Role::create(['name' => 'admin']);
        $karyawanRole = Role::create(['name' => 'karyawan']);

        // 2. Buat 1 User Admin (Biasanya admin tidak ada di tabel employee)
        User::factory()->admin()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
        ]);

        // 3. Buat 10 User Karyawan SEKALIGUS Data Employee-nya
        User::factory(10)
            ->karyawan() // Memanggil state role karyawan di UserFactory
            ->has(Employee::factory(), 'employee') // 'employee' adalah nama fungsi relasi di Model User
            ->create();
    }
}
