<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\StorePackage;
use App\Models\StoreSubscription;
use App\Models\StoreType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $categorise = ProductCategory::root()->get();
        $new_products = Product::language()->where('product_type', 'model')->latest()->with(['rates'])->take(6)->get();
        $latest_fabrics_products = Product::language()->where('store_type_id', 12)->latest()->with(['rates'])->get();
        $count = $latest_fabrics_products->count();

        if ($count > 3) {
            $latest_fabrics_products = $latest_fabrics_products->random(3);
        } else {
            $latest_fabrics_products = $latest_fabrics_products->random($count);
        }

        $custom_made_products = Product::language()->where('product_type', 'custom_made')->latest()->with(['rates'])->take(3)->get();
        $stores_types = StoreType::language()->where('store_type_status', 'active')->get()->take(3);

        return view('Website.customer.home', compact(['categorise', 'new_products', 'custom_made_products', 'stores_types', 'latest_fabrics_products']));
    }

    public function storeType($store_type_slug)
    {
        $store_type_model = StoreType::where('slug', $store_type_slug)->first();
        return view('Website.stores.index')
            ->with('store_type_slug', $store_type_slug)->with('products', false);
    }

    public function storeDetails($store_type_slug, $store_name_slug)
    {
        if ($store_type_slug == 'fabrics-materials') {
            return view('Website.stores.index', ['store_type_slug' => $store_type_slug, 'store_name_slug' => $store_name_slug, 'products' => true]);
        }

        return view('Website.stores.details', ['store_type_slug' => $store_type_slug, 'store_name_slug' => $store_name_slug]);
    }

    public function storeProductDetails($store_type_slug, $store_name_slug, $product_id)
    {
        $product = Product::language()
            ->where('is_delete', '0')
            ->where('product_status', 'active')
            ->where('id', $product_id)->first();

        $store = Store::language()
            ->where('is_delete', '0')
            ->where('store_status', 'active')
            ->where('slug', $store_name_slug)
            ->where('store_type_slug', $store_type_slug)
            ->where('id', $product->store_id)->first();

        $store_type = StoreType::language()
            ->where('id', $store->store_type_id)
            ->where('slug', $store_type_slug)->first();

        $store_admin = User::where('id', $store->store_admin)
            ->where('is_delete', '0')
            ->where('user_type', 'store_admin')
            ->first();

        $related_products = $product->related_model_products;

        if ($product->product_type == 'custom_made') {
            return view('Website.products.custom-product-details', [
                'product' => $product,
                'custom' => $product->custom,
                'store' => $store,
                'store_type' => $store_type,
                'store_admin' => $store_admin,
                'related_products' => $related_products,
                'store_type_slug' => $store_type_slug,
                'store_name_slug' => $store_name_slug,
                'product_id' => $product_id
            ]);
        }

        return view('Website.stores.product-deatils', [
            'product' => $product,
            'store' => $store,
            'store_type' => $store_type,
            'store_admin' => $store_admin,
            'related_products' => $related_products,
            'store_type_slug' => $store_type_slug,
            'store_name_slug' => $store_name_slug,
            'product_id' => $product_id
        ]);
    }

    public function loandingHome()
    {
        $gold_store_package = StorePackage::language()->where('package_type', 'gold')->first();
        $silver_store_package = StorePackage::language()->where('package_type', 'silver')->first();
        $free_store_package = StorePackage::language()->where('package_type', 'free')->first();

        return view('Website.customer.loanding-home', [
            'gold_store_package' => $gold_store_package,
            'silver_store_package' => $silver_store_package,
            'free_store_package' => $free_store_package,
        ]);
    }

    public function createStorePage(Request $request, $package_id = null)
    {
        $email = $request->email;
        $stores_types = StoreType::where('is_delete', 0)->get();

        return view('Website.customer.create-store', compact([
            'email',
            'package_id',
            'stores_types'
        ]));
    }

    public function createStore(Request $request)
    {
        $request->validate([
            "store_title" => ['required', 'string'],
            "store_url" => ['required', 'string', 'regex:/^[A-Za-z0-9]+(?:-[A-Za-z0-9]+)+$/'],
            "entity_type" => ['required'],
            "commercial_registration_link" => ['required'],
            "id_number" => ['required'],
            "registration_number_in_trusted" => ['required'],
            "store_manager" => ['required'],
            "phone_number" => ['required', 'unique:stores,phone_number'],
            "phone_number" => ['required', 'unique:users,phone_number'],
            "email" => ['required', 'unique:stores,email'],
            "password" => ['required'],
            "confirm_password" => ['required'],
            "package_id" => ['nullable'],
        ]);

        //Here the scenario of storing store and link packages

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->store_manager,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'user_type' => 'store_admin',
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('store_admin');
        }

        $store_type = StoreType::where('id', $request->entity_type)->first();

        if ($user && $store_type) {

            $store_type_slug = $store_type->slug;

            $store = Store::create([
                #IDs
                'store_admin' => $user->id,
                'store_type_id' => $store_type->id,
                'store_type_slug' => $store_type_slug,
                // 'slug' => $store_slug,
                // 'payment_type_id' => $request->payment_type_id,

                #Basic Data
                'store_name_ar' =>  $request->store_title,
                'store_name_en' => $request->store_title,
                // 'store_details_en' => $request->store_description_en,
                // 'store_details_ar' => $request->store_description_ar,
                // 'store_address_en' => $request->address_en,
                // 'store_address_ar' => $request->address_ar,
                'store_logo' => 'user_id.png',
                'store_domain' => 'ok',
                'phone_number' => $request->phone_number,
                'email' => $user->email,
                // 'store_currency' => $request->currency_id,
                // 'store_country' => $request->store_country,
                // 'store_city' => $request->store_city,
                'commercial_record' => $request->commercial_registration_link,
                'registration_number_in_trusted' => $request->registration_number_in_trusted,
                'id_number' => $request->id_number,
                'store_status' => 'active',

                #Subcription Data
                'subscription_start_date' => now(),
                'subscription_end_date' => date('Y-m-d', strtotime('+30 days', strtotime(now()))),
                'subscription_package_id' => $request->package_id ?? 1,
                'is_trail' => '1',
                'is_delete' => 0,
            ]);


            if ($store) {

                $domain = asset('') . 'stores/' . $store_type_slug . '/details/' . $store->slug;

                $store->update(['store_domain' => $domain]);

                if ($user) {
                    $user->update([
                        'store_id' => $store->id,
                        'store_type_id' => $store->store_type_id,
                    ]);
                }

                StoreSubscription::create([
                    'store_id' => $store->id,
                    'package_id' => $store->subscription_package_id ?? 1,
                    'subscription_start_date' => $store->subscription_start_date,
                    'subscription_end_date' =>  $store->subscription_start_date,
                    'subscription_status' => 'active',
                    'is_delete' => '0'
                ]);
            }
        }

        return true;
    }

    public function storeYourDesignerDetails()
    {
        return view('Website.store-your-designers-details');
    }

    public function sizeDe1()
    {
        return view('Website.size_details');
    }

    public function sizeDe2()
    {
        return view('Website.size_details_1');
    }

    public function sizeDe3()
    {
        return view('Website.size_details_2');
    }

    public function sizeDe4()
    {
        return view('Website.size_details_3');
    }

    public function sizeDe5()
    {
        return view('Website.size_details_4');
    }

    public function sizeDe6()
    {
        return view('Website.size_details_5');
    }

    public function sizeDe7()
    {
        return view('Website.size_details_6');
    }
}
