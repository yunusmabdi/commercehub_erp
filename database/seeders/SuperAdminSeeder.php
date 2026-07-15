<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            $user->assignRole('Super Admin');
        }
    }
}