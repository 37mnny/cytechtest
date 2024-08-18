<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'company_id' => 1, // ここで適切な company_id を設定
            'product_name' => 'Sample Product',
            'price' => 1000,
            'stock' => 50,
            'comment' => 'This is a sample product.',
            'img_path' => 'sample.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
