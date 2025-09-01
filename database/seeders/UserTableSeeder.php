<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $tenants = [];


        for ($i = 0; $i < 5; $i++) {
           $name = $faker->name;
            $tenants[] = [
                'name' =>$name,
                'domain' => Str::slug($name) ,
                'slug'=>Str::slug($name),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tenants')->insert($tenants);

        $tenantIds = DB::table('tenants')->pluck('id');

        $users = [];

        // Create super admin
        $users[] = [
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Create users for each tenant
        foreach ($tenantIds as $tenantId) {
            
            // Create 3-8 regular users per tenant
            $userCount = $faker->numberBetween(3, 8);
            for ($i = 0; $i < $userCount; $i++) {
                $users[] = [
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make('password'),                    
                    'tenant_id' => $tenantId,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('users')->insert($users);
    }
}
