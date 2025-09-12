<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role_name' => 'admin', 'description' => 'Administrator with full access'],
            ['role_name' => 'Cashier', 'description' => 'Kasir'],
            ['role_name' => 'Chef', 'description' => 'Koki'],
            ['role_name' => 'Customer', 'description' => 'Pelanggan'],
        ];

        DB::table('roles')->insert($roles);
    }
}
