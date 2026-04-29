<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $site = Site::firstOrCreate(
            ['slug' => 'azurecly_devolopers'],
            [
                'name'  => 'Azurecly Devolopers',
                'email' => 'azureclyexam@azurecly.com',
            ]
        );

        User::updateOrCreate(
            ['email' => 'adminkaban@azurecly.com'],
            [
                'name'     => 'admin-kaban',
                'password' => Hash::make('adminprop1908'),
                'role'     => 'admin',
                'site_id'  => $site->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'humasazurecly@azurecly.com'],
            [
                'name'     => 'humas-azurecly',
                'password' => Hash::make('hazure#08'),
                'role'     => 'humas',
                'site_id'  => $site->id,
            ]
        );
    }
}