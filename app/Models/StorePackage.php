<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_name_en',
        'package_name_ar',
        'package_description_en',
        'package_description_ar',
        'package_price',
        'package_currency',
    ];

    public function getName()
    {
        if (app()->getLocale() == 'ar')
            return $this->package_name_ar;
        else
            return $this->package_name_en;
    }

    public function getDescription()
    {
        if (app()->getLocale() == 'ar')
            return $this->package_description_ar;
        else
            return $this->package_description_en;
    }

    public function items()
    {
        return $this->hasMany('App\Models\StorePackageItem', 'package_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'package_currency');
    }

    public function storeSubscriptions()
    {
        return $this->hasMany(StoreSubscription::class, 'package_id', 'id');
    }

    public function scopeLanguage($query)
    {
        if (session('lang') == 'ar') {
            $lang = config('database.store-package.ar');
        } else {
            $lang = config('database.store-package.en');
        }

        return $query->select($lang);
    }
}
