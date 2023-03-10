<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #Admin ==> Admin Dashbaord

        $country_id = random_int(1, 16);

        DB::table('users')->insert(
            [
                'store_id' => null,
                'store_type_id' => null,
                'slug' => Str::slug('pro_amro_arkam') . "_" . Str::uuid(),
                'name' => 'pro amro arkam',
                'email' => 'admin@gmail.com',
                'country_id' => 1,
                'city_id' => 1,
                'user_type' => 'admin',
                'image' => 'user_1.png',
                'user_status' => 'active',
                'gender' => 'male',
                'birthdate' => date("Y-m-d", mktime(0, 0, 0, 7, 9, 1999)),
                'phone_number' => '0599916672',
                'address_1' => 'palestine',
                'address_2' => 'gaza',
                'postCode' => 9990300,
                'password' => Hash::make('123456'),
                'social_login_provider' => null,
                'social_login_provider_code' => null,
                'email_verified_at' => now(),
                'is_delete' => '0',
            ]
        );

        #Store Admin ==> Store Admin Dashbaord

        DB::table('users')->insert(
            [
                'store_id' => null,
                'store_type_id' => null,
                'slug' => Str::slug('pro_amro_arkam') . "_" . Str::uuid(),
                'name' => 'pro amro arkam',
                'email' => 'store@gmail.com',
                'country_id' => 1,
                'city_id' => 1,
                'user_type' => 'store_admin',
                'image' => 'user_1.png',
                'user_status' => 'active',
                'gender' => 'male',
                'birthdate' => date("Y-m-d", mktime(0, 0, 0, 7, 9, 1999)),
                'phone_number' => '0599916638',
                'address_1' => 'palestine',
                'address_2' => 'gaza',
                'postCode' => 9990300,
                'password' => Hash::make('123456'),
                'social_login_provider' => null,
                'social_login_provider_code' => null,
                'email_verified_at' => now(),
                'is_delete' => '0',
            ]
        );


        // $x = 3;

        // while ($x <= 5) {

        //     $random_country_id = random_int(1, 6);

        //     if ($random_country_id == 1) {
        //         $random_city_id = random_int(1, 15);
        //     }

        //     if ($random_country_id == 2) {
        //         $random_city_id = random_int(16, 19);
        //     }

        //     if ($random_country_id == 3) {
        //         $random_city_id = random_int(20, 23);
        //     }

        //     if ($random_country_id == 4) {
        //         $random_city_id = random_int(24, 27);
        //     }

        //     if ($random_country_id == 5) {
        //         $random_city_id = random_int(28, 31);
        //     }

        //     if ($random_country_id == 6) {
        //         $random_city_id = random_int(32, 35);
        //     }


        //     DB::table('users')->insert(
        //         [
        //             'name' => 'Store_User_' . $x,
        //             'slug' => Str::slug('Store_User_' . $x),
        //             'email' => 'store' . $x . '@gmail.com',
        //             'country_id' => $random_country_id,
        //             'city_id' => $random_city_id,
        //             'address_1' => 'Palstine',
        //             'address_2' => 'Gaza',
        //             'phone_number' => '+9725999' . random_int(11111, 99999),
        //             'image' => 'user_1.png',
        //             // 'social_login_provider' => ,
        //             'user_type' => 'store_admin',
        //             'gender' => 'male',
        //             // 'social_login_provider_code' => ,
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('123456789'),
        //         ]

        //     );

        //     $x = $x + 1;
        // }

        // while ($x <= 60) {

        //     $random_country_id = random_int(1, 6);

        //     if ($random_country_id == 1) {
        //         $random_city_id = random_int(1, 15);
        //     }

        //     if ($random_country_id == 2) {
        //         $random_city_id = random_int(16, 19);
        //     }

        //     if ($random_country_id == 3) {
        //         $random_city_id = random_int(20, 23);
        //     }

        //     if ($random_country_id == 4) {
        //         $random_city_id = random_int(24, 27);
        //     }

        //     if ($random_country_id == 5) {
        //         $random_city_id = random_int(28, 31);
        //     }

        //     if ($random_country_id == 6) {
        //         $random_city_id = random_int(32, 35);
        //     }

        //     DB::table('users')->insert(
        //         [
        //             'name' => 'Customer_User_' . $x,
        //             'email' => 'customer' . $x . '@gmail.com',
        //             'slug' => Str::slug('Customer_User_' . $x),
        //             'country_id' => $random_country_id,
        //             'city_id' => $random_city_id,
        //             'address_1' => 'Palstine',
        //             'address_2' => 'Gaza',
        //             'phone_number' => '+9725999' . random_int(11111, 99999),
        //             'image' => 'user_1.png',
        //             // 'social_login_provider' => ,
        //             'user_type' => 'customer',
        //             'gender' => 'male',
        //             // 'social_login_provider_code' => ,
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('123456789'),
        //         ]
        //     );

        //     $x = $x + 1;
        // }
    }
}
