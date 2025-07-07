<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@chicchevronbeauty.com',
                'password' => Hash::make('Admin@2024'),
            ],
            [
                'name' => 'Sales Manager',
                'email' => 'sales@chicchevronbeauty.com',
                'password' => Hash::make('Sales@2024'),
            ],
            [
                'name' => 'Inventory Manager',
                'email' => 'inventory@chicchevronbeauty.com',
                'password' => Hash::make('Inventory@2024'),
            ],
        ];

        foreach ($admins as $admin) {
            // Check if admin already exists
            if (!Admin::where('email', $admin['email'])->exists()) {
                Admin::create($admin);
                $this->command->info("Admin created: {$admin['email']}");
            } else {
                $this->command->info("Admin already exists: {$admin['email']}");
            }
        }

        $this->command->warn('Admin Credentials:');
        $this->command->warn('================');
        foreach ($admins as $admin) {
            $this->command->warn("Email: {$admin['email']}");
            $this->command->warn("Password: " . str_replace(Hash::make(''), '', $admin['password']));
            $this->command->warn('---');
        }
        $this->command->warn('⚠️  Please change these passwords after first login!');
    }
}