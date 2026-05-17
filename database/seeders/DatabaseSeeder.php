<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KategoriBarang;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin (registrasi manual lewat database)
        User::create([
            'nama_lengkap' => 'Administrator LondoBell',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'nomor_hp' => '081234567890',
            'role' => 'admin',
            'id_admin' => 'ADMIN001',
        ]);

        // Kategori default
        KategoriBarang::insert([
            ['nama_kategori' => 'Elektronik', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Pakaian', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Makanan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Minuman', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Peralatan Rumah', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}