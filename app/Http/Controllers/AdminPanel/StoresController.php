<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Country;
use App\Models\StorePackage;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Currency;
use App\Models\PaymentType;
use App\Models\StoreSubscription;
use App\Models\StoreType;
use App\Models\User;
use Illuminate\Support\Str;

class StoresController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stores = Store::where('is_delete', 0)->get();
        return view('AdminPanel.stores.index')->with('stores', $stores);
    }


    public function create()
    {
        $payment_types = PaymentType::where('is_delete', 0)->where('status', 'active')->get();
        $stores_types = StoreType::language()->where('store_type_status', 'active')->get();

        $currencies = Currency::where('is_delete', 0)->get();
        $admins = User::where('is_delete', 0)->where('user_type', 'store_admin')->where('user_status', 'active')->orderBy('created_at', 'desc')->get();
        $countries = Country::where('status', 'active')->where('is_delete', 0)->get();
        $packages = StorePackage::where('package_status', 'active')->where('is_delete', 0)->get();
        return view('AdminPanel.stores.create')->with('countries', $countries)->with('currencies', $currencies)->with('packages', $packages)
            ->with('payment_types', $payment_types)->with('stores_types', $stores_types)->with('admins', $admins);
    }

    public function getCity(Request $request)
    {
        $data['states'] = City::where('status', 'active')->where("country_id", $request->country_id)
            ->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'store_name_ar' => 'required|string|max:255',
            'store_name_en' => 'required|string|max:255',
            // 'store_domain' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'store_admin' => 'required|numeric|exists:users,id',
            'store_type_id' => 'required|numeric|exists:store_types,id',
            'store_country' => 'required|numeric|exists:countries,id',
            'store_city' => 'required|numeric|exists:cities,id',
            'payment_type_id' => 'required|numeric|exists:payment_types,id',
            'subscription_package_id' => 'required|numeric|exists:store_packages,id',
            'subscription_start_date' => 'required|date',
            'store_status' => 'nullable|in:on,off',

            'store_description_ar' => 'nullable|string',
            'store_description_en' => 'nullable|string',
            'address_ar' => 'nullable|string',
            'address_en' => 'nullable|string',
            'currency_id' => 'required|numeric|exists:currencies,id',

            'registration_number_in_trusted' => 'string',
            //   'commercial_record' => 'string',
            'id_number' => 'string',
        ]);

        if ($request->store_status == 'on') {
            $store_status = 'active';
        } else {
            $store_status = 'inactive';
        }

        $store_type_slug = StoreType::find($request->store_type_id)->slug;

        // $store_slug = Str::slug($request->store_name_en) . "_" . Str::uuid();


        $user = User::find($request->store_admin);

        $store = Store::create([
            #IDs
            'store_admin' => $request->store_admin,
            'store_type_id' => $request->store_type_id,
            'store_type_slug' => $store_type_slug,
            // 'slug' => $store_slug,
            'payment_type_id' => $request->payment_type_id,

            #Basic Data
            'store_name_ar' =>  $request->store_name_ar,
            'store_name_en' => $request->store_name_en,
            'store_details_en' => $request->store_description_en,
            'store_details_ar' => $request->store_description_ar,
            'store_address_en' => $request->address_en,
            'store_address_ar' => $request->address_ar,
            'store_logo' => 'user_id.png',
            'store_domain' => 'ok',
            'phone_number' => $request->phone_number,
            'email' => $user->email,
            'store_currency' => $request->currency_id,
            'store_country' => $request->store_country,
            'store_city' => $request->store_city,
            'commercial_record' => $request->commercial_record,
            'registration_number_in_trusted' => $request->registration_number_in_trusted,
            'id_number' => $request->id_number,
            'store_status' => $store_status,

            #Subcription Data
            'subscription_start_date' => $request->subscription_start_date,
            'subscription_end_date' => date('Y-m-d', strtotime('+30 days', strtotime($request->subscription_start_date))),
            'subscription_package_id' => $request->subscription_package_id,
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
                'package_id' => $request->subscription_package_id,
                'subscription_start_date' => $request->subscription_start_date,
                'subscription_end_date' =>  date('Y-m-d', strtotime('+30 days', strtotime($request->subscription_start_date))),
                'subscription_status' => 'active',
                'is_delete' => '0'
            ]);
        }

        $this->massage('success', 'Stores added successfully', '???? ?????????? ????????????????  ??????????');

        return redirect()->route('admin.stores.index');
    }

    public function edit($id)
    {
        $store = Store::find($id);
        if (is_null($store) || $store->is_delete == 1) {
            $this->massage('error', 'Store not found', '???????????? ?????? ????????????');
            return redirect()->back();
        }

        $payment_types = PaymentType::where('is_delete', 0)->where('status', 'active')->get();
        $stores_types = StoreType::language()->where('store_type_status', 'active')->get();

        $currencies = Currency::where('is_delete', 0)->get();
        $countries = Country::where('status', 'active')->where('is_delete', 0)->get();
        $cities = City::where('status', 'active')->where('is_delete', 0)->where('country_id', $store->store_country)->get();
        $packages = StorePackage::where('package_status', 'active')->where('is_delete', 0)->get();
        $admins = User::where('is_delete', 0)->where('user_type', 'store_admin')->where('user_status', 'active')->orderBy('created_at', 'desc')->get();

        return view('AdminPanel.stores.edit')->with('store', $store)->with('currencies', $currencies)->with('cities', $cities)
            ->with('payment_types', $payment_types)->with('stores_types', $stores_types)->with('countries', $countries)->with('packages', $packages)->with('admins', $admins);
    }

    public function update(Request $request, $id)
    {

        $store = Store::find($id);

        if (is_null($store)  || $store->is_delete == 1) {
            $this->massage('error', 'Store not found', '???????????? ?????? ????????????');
            return redirect()->back();
        }

        $this->validate($request, [
            // 'store_name_ar' => 'required|string|max:255',
            // 'store_name_en' => 'required|string|max:255',
            // 'store_domain' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            // 'store_admin' => 'required|numeric|exists:users,id',
            // 'store_type_id' => 'required|numeric|exists:store_types,id',
            'store_country' => 'required|numeric|exists:countries,id',
            'store_city' => 'required|numeric|exists:cities,id',
            'subscription_package_id' => 'required|numeric|exists:store_packages,id',
            'subscription_start_date' => 'required|date',
            'payment_type_id' => 'required|numeric|exists:payment_types,id',
            'store_status' => 'nullable|in:on,off',

            'store_description_ar' => 'nullable|string',
            'store_description_en' => 'nullable|string',
            'address_ar' => 'nullable|string',
            'address_en' => 'nullable|string',

            'currency_id' => 'required|numeric|exists:currencies,id',

            'commercial_record' => 'string',
            'registration_number_in_trusted' => 'string',
            'id_number' => 'string',

        ]);

        if ($request->store_status == 'on') {
            $store_status = 'active';
        } else {
            $store_status = 'inactive';
        }

        $store->update([
            #IDs
            // 'store_admin' => $request->store_admin,
            // 'store_type_id' => $request->store_type_id,
            // 'store_type_slug' => $store_type_slug,
            // 'slug' => $store_slug,
            'payment_type_id' => $request->payment_type_id,

            #Basic Data
            // 'store_name_ar' =>  $request->store_name_ar,
            // 'store_name_en' => $request->store_name_en,
            'store_details_en' => $request->store_description_en,
            'store_details_ar' => $request->store_description_ar,
            'store_address_en' => $request->address_en,
            'store_address_ar' => $request->address_ar,
            'phone_number' => $request->phone_number,
            'store_currency' => $request->currency_id,
            'store_country' => $request->store_country,
            'store_city' => $request->store_city,
            'commercial_record' => $request->commercial_record,
            'registration_number_in_trusted' => $request->registration_number_in_trusted,
            'id_number' => $request->id_number,
            'store_status' => $store_status,

            #Subcription Data
            'subscription_start_date' => $request->subscription_start_date,
            'subscription_end_date' => date('Y-m-d', strtotime('+30 days', strtotime($request->subscription_start_date))),
            'subscription_package_id' => $request->subscription_package_id,
            'is_trail' => '1',
            'is_delete' => 0,


            // 'store_logo' => 'user_id.png',
            // 'store_domain' => 'ok',
            // 'email' => $user->email,
        ]);

        $active_store_subscription = $store->store_subscriptions->where('subscription_status', 'active')->first();

        if (!is_null($active_store_subscription) && $active_store_subscription->package_id == $request->subscription_package_id) {
            $active_store_subscription->update([
                'subscription_start_date' => $request->subscription_start_date,
                'subscription_end_date' =>  date('Y-m-d', strtotime('+30 days', strtotime($request->subscription_start_date))),
            ]);
        } else {
            if (!is_null($active_store_subscription)) {
                $active_store_subscription->update([
                    'subscription_status' => 'inactive',
                ]);

                StoreSubscription::create([
                    'store_id' => $store->id,
                    'package_id' => $request->subscription_package_id,
                    'subscription_start_date' => $active_store_subscription->subscription_start_date,
                    'subscription_end_date' => date('Y-m-d', strtotime('+1 month', strtotime($active_store_subscription->subscription_start_date))),
                    'subscription_status' => 'active',
                    'is_delete' => '0'
                ]);
            }

            StoreSubscription::create([
                'store_id' => $store->id,
                'package_id' => $request->subscription_package_id,
                'subscription_start_date' => $request->subscription_start_date,
                'subscription_end_date' => date('Y-m-d', strtotime('+1 month', strtotime($request->subscription_start_date))),
                'subscription_status' => 'active',
                'is_delete' => '0'
            ]);
        }

        $this->massage('success', 'Store data has been modified successfully', '???? ?????????? ???????????? ????????????  ??????????');

        return redirect()->route('admin.stores.index');
    }

    public function destroy($id)
    {
        $store = Store::find($id);

        if (is_null($store)  || $store->is_delete == 1) {
            $this->massage('error', 'Store not found', '???????????? ?????? ????????????');
            return redirect()->back();
        }

        $store->is_delete = 1;
        $store->save();
        $this->massage('success', 'Store deleted successfully', '???? ?????? ???????????? ??????????');
        return redirect()->back();
    }


    public function change_status($id)
    {
        $store = Store::find($id);

        if (is_null($store)  || $store->is_delete == 1) {
            $this->massage('error', 'Store not found', '???????????? ?????? ??????????');
            return redirect()->back();
        }

        if ($store->store_status == 'active') {
            $store->store_status = 'inactive';
        } else {
            $store->store_status = 'active';
        }

        $store->save();
        $this->massage('success', 'Store status changed successfully', '???? ?????????? ???????? ???????????? ??????????');
        return redirect()->back();
    }
}
