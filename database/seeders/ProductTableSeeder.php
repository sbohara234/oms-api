<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $faker = Faker::create();

        $products = [];

        $tenantIds = DB::table('tenants')->pluck('id');

        for ($i = 0; $i < 15; $i++) {
            $tenantId = $faker->randomElement($tenantIds);
            $quantity = $faker->numberBetween(150, 900);
            $unitPrice = $faker->numberBetween(150.00, 900.00);
            $unit = $faker->randomElement(['box', 'dozen','pair','set','bottle','kg','litre']);
           $name = $faker->name;
            $products[] = [
                'name' =>$name,
                    'tenant_id'=>$tenantId,
                    'description'=>$name,
                    'quantity'=>$quantity,
                    'product_code'=>Str::slug($name).'-'.Str::slug($unit),
                    'product_unit'=>$unit,
                    'b2c_price_per_unit'=>$unitPrice,
                    'b2b_price_per_unit'=>$unitPrice+5,
                    
                'created_at' => now(),
                'updated_at' => now(),
            ];
            // dd($products);
        }

        DB::table('products')->insert($products);
    }
}
