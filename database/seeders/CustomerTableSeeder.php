<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $faker = Faker::create();

        $tenantIds = DB::table('tenants')->pluck('id');

        for ($i = 0; $i < 5; $i++) {
            $tenantId = $faker->randomElement($tenantIds);
           $name = $faker->name;
           $type = $faker->randomElement(['b2b', 'b2c']);
            $tenants[] = [
                'name' =>$name,
                'tenant_id'=>$tenantId,
                'email' => $faker->unique()->safeEmail ,
                'type'=>$type,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('customers')->insert($tenants);
    }
}
