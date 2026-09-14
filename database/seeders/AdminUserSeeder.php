<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@tokoonline.com';

        $exists = User::where('email', $email)->exists();

        if (!$exists) {
            User::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => Hash::make('admin12'),
                'role' => 'admin',
            ]);

            $this->command->info('Akun admin berhasil dibuat: ' . $email);
        } else {
            $this->command->info('Akun admin sudah ada: ' . $email);
        }
    }
}
