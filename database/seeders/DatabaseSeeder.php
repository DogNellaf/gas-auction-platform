<?php

namespace Database\Seeders;

use App\Models\LegalForm;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Legal forms
        collect(['ООО', 'ЗАО', 'ОАО', 'ИП'])->each(
            fn(string $title) => LegalForm::firstOrCreate(['title' => $title])
        );

        // Admin account
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'         => 'admin',
                'company_name' => 'Gas Auction Platform',
                'form_id'      => 1,
                'phone'        => '+70000000000',
                'password'     => Hash::make('password'),
                'is_approved'  => true,
                'is_admin'     => true,
            ]
        );

        // Demo approved user
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name'         => 'demo_user',
                'company_name' => 'Demo Company LLC',
                'form_id'      => 1,
                'phone'        => '+70000000001',
                'password'     => Hash::make('password'),
                'is_approved'  => true,
                'is_admin'     => false,
            ]
        );
    }
}
