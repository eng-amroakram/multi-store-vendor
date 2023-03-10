<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreSubscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\StoreType;
use App\Models\User;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores_types = [
            "Fashion designer" => "مصمم أزياء",
            "Appearance Expert" => "خبير مظهر",
            "Photographer" => "تصوير فوتوغراف",
            "Model" => "عارض أزياء",
            "Brand clothes" => "براند ملابس",
            "Trade Mark" => "علامة تجارية",
            "Leather Goods" => "مصنوعات جلدٌة",
            "Brand Hand Made" => " براند هاند ميد",
            "Sewing Workshops" => "مشاغل خياطة",
            "Clothes Factory" => "مصنع ملابس",
            "Embroidery Lab" => "معمل تطريز",
            "Fabrics && Materials" => "أقمشة وخامات",
            "Sewing Accessories" => "اكسسوارت ولوازم الخياطة",
            "Jewelry" => "مجوهرات",
            "Perfume" => "عطور",
            "Make-up 'Beauty Tools'" => "مكياج ادوات تجميل"
        ];

        $sluging = [
            1 => "Fashion designer",
            2 => "Appearance Expert",
            3 => "Photographer",
            4 => "Model",
            5 => "Brand clothes",
            6 => "Trade Mark",
            7 => "Leather Goods",
            8 => "Brand Hand Made",
            9 => "Sewing Workshops",
            10 => "Clothes Factory",
            11 => "Embroidery Lab",
            12 => "Fabrics && Materials",
            13 => "Sewing Accessories",
            14 => "Jewelry",
            15 => "Perfume",
            16 => "Make-up 'Beauty Tools'",
        ];

        foreach ($stores_types as $en_store_type => $ar_store_type) {
            DB::table('store_types')->insert([
                'store_type_ar' => $ar_store_type,
                'store_type_en' => $en_store_type,
                'slug' => Str::slug($en_store_type) . "_" . Str::uuid(),
                'image' => 'st' . random_int(1, 4) . '.png',
                'banner_section' => 'active',
                'service_section' => 'active',
                'filter_section' => 'active',
                'store_type_status' => 'active',
                'is_delete' => '0'
            ]);
        }

        $store_type = StoreType::find(2);
        $store_slug_appe = Str::slug('store_' . 2) . "_" . Str::uuid();
        $random_int = random_int(111111111, 999999999);

        DB::table('stores')->insert(
            [
                'store_admin' => 2,
                'store_type_id' => 2,
                'store_type_slug' => $store_type->slug,
                'slug' => $store_slug_appe,
                'payment_type_id' => null,
                'store_name_ar' => Str::slug('المتجر' . '_' . '2'),
                'store_name_en' => Str::slug('store_' . '2'),
                'store_details_en' => $this->getRandomText(300, 'en'),
                'store_details_ar' => $this->getRandomText(300, 'en'),
                'store_address_en' => Str::random(10),
                'store_address_ar' => $this->getRandomText(10, 'en'),
                'store_logo' => 'team_member_' . random_int(2, 5) . '.png',
                'store_domain' => asset('') . 'customer/stores/' . $store_type->slug . '/details/' . $store_slug_appe,
                'phone_number' => '+9725999' . random_int(11111, 99999),
                'email' => 'store' . '2' . '@gmail.com',
                'store_currency' => random_int(1, 4),
                'store_country' => 1,
                'store_city' => 1,

                'registration_number_in_trusted' => $random_int,
                'commercial_record' => $random_int,
                'id_number' => $random_int,

                'store_status' => 'active',

                'subscription_start_date' => now(),
                'subscription_end_date' => now()->addDays(10),
                'subscription_package_id' => '3',

                'is_trail' => '1',
                'is_delete' => '0',
            ]
        );

        $store_app = Store::where('store_admin', '2')->first();
        $user = User::find(2);

        DB::table('store_subscriptions')->insert([
            'store_id' => $store_app->id,
            'package_id' => '3',
            'subscription_start_date' => now(),
            'subscription_end_date' => now(),
            'subscription_status' => 'active',
            'is_delete' => 0
        ]);

        $user->assignRole('store_admin');

        $user->update([
            'store_id' => $store_app->id,
            'store_type_id' => $store_app->store_type_id,
        ]);


        $stores_types = StoreType::all();
        $users = User::where('user_type', 'store_admin')->where('id', '!=', 2)->get();

        foreach ($users as $user) {
            $random_country_id = random_int(1, 6);

            if ($random_country_id == 1) {
                $random_city_id = random_int(1, 15);
            }

            if ($random_country_id == 2) {
                $random_city_id = random_int(16, 19);
            }

            if ($random_country_id == 3) {
                $random_city_id = random_int(20, 23);
            }

            if ($random_country_id == 4) {
                $random_city_id = random_int(24, 27);
            }

            if ($random_country_id == 5) {
                $random_city_id = random_int(28, 31);
            }

            if ($random_country_id == 6) {
                $random_city_id = random_int(32, 35);
            }

            $store_type_id = random_int(1, 16);

            $store_type = StoreType::find($store_type_id);

            if ($store_type) {
                $store_type_slug = $store_type->slug;
            }

            $store_slug = Str::slug('store_' . $user->id) . "_" . Str::uuid();

            $random_int = random_int(111111111, 999999999);

            DB::table('stores')->insert(
                [
                    'store_admin' => $user->id,
                    'store_type_id' => $store_type_id,
                    'store_type_slug' => $store_type_slug,
                    'slug' => $store_slug,
                    'payment_type_id' => null,
                    'store_name_ar' => Str::slug('المتجر' . '_' . $user->id),
                    'store_name_en' => Str::slug('store_' . $user->id),
                    'store_details_en' => $this->getRandomText(300, 'en'),
                    'store_details_ar' => $this->getRandomText(300, 'en'),
                    'store_address_en' => Str::random(10),
                    'store_address_ar' => $this->getRandomText(10, 'en'),
                    'store_logo' => 'team_member_' . random_int(2, 5) . '.png',
                    'store_domain' => asset('') . 'customer/stores/' . $store_type_slug . '/details/' . $store_slug,
                    'phone_number' => '+9725999' . random_int(11111, 99999),
                    'email' => 'store' . $user->id . '@gmail.com',
                    'store_currency' => random_int(1, 4),
                    'store_country' => $random_country_id,
                    'store_city' => $random_city_id,

                    'registration_number_in_trusted' => $random_int,
                    'commercial_record' => $random_int,
                    'id_number' => $random_int,

                    'store_status' => 'active',

                    'subscription_start_date' => now(),
                    'subscription_end_date' => now()->addDays(10),
                    'subscription_package_id',

                    'is_trail' => '1',
                    'is_delete' => '0',
                ]
            );

            $store = Store::where('store_admin', $user->id)->first();

            DB::table('store_subscriptions')->insert([
                'store_id' => $store->id,
                'package_id' => '1',
                'subscription_start_date' => now(),
                'subscription_end_date' => now(),
                'subscription_status' => 'active',
                'is_delete' => 0
            ]);

            $user->assignRole('store_admin');

            $user->update([
                'store_id' => $store->id,
                'store_type_id' => $store->store_type_id,
            ]);
        }
    }

    public function getRandomText($n, $lang)
    {

        if ($lang == 'ar') {
            $characters = '0123456789أبتثجحخدذرزسشصضطظعغفقكلمنهـوي';
        } else {

            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}
