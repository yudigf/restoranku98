<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Makanan', 'description' => 'Kategori untuk semua jenis makanan'],
            ['category_name' => 'Minuman', 'description' => 'Kategori untuk semua jenis minuman'],
        ];

        DB::table('categories')->insert($categories);
    }
}
