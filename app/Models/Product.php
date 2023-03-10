<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        #IDs
        'store_admin',
        'store_id',
        'store_type_id',
        'slug',

        #Basic Data
        'product_name_en',
        'product_name_ar',
        'product_description_en',
        'product_description_ar',
        'product_type',
        'product_category',
        'product_serial_number',
        'product_vat',
        'product_vat_value',
        'product_price',
        'product_price_after_vat',
        'wholesale_price',
        'product_size',
        'in_stock',
        'product_3d_image',
        'product_main_image',
        'product_status',

        #Affiliate Data
        'is_affiliate',
        'affiliate_type',
        'affiliate_value',

        #Is Model Deleted
        'is_delete',
    ];
    protected $casts = ['product_size' => 'array'];

    protected static function booted()
    {
        static::creating(function (Product $item) {
            $item->slug = preg_replace('/\s+/', '_', $item->product_name_en) . "_" . Str::uuid();
        });
    }

    public function getLangFields()
    {
        if (session('lang') == 'ar') {
            return $lang = config('database.products.ar');
        } else {
            return $lang =  config('database.products.en');;
        }
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(ProductColor::class, 'colors_of_product', 'product_id', 'color_id', 'id', 'id')->where('is_delete', 0);
    }

    public function color(): HasMany
    {
        return $this->hasMany('colors_of_product', 'product_id', 'id');
    }

    public function custom(): HasOne
    {
        return $this->hasOne(ProductCustomMade::class, 'product_id', 'id');
    }

    #Scope
    public function scopeActive($query)
    {
        return $query->where('product_status', 'active')->where('is_delete', '0');
    }

    //Store Relationship
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    //Images relation
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    //Comments relation
    public function comments()
    {
        return $this->hasMany(ProductComment::class, 'product_id', 'id');
    }

    //Rates relation
    public function rates()
    {
        return $this->hasMany(ProductRate::class, 'product_id', 'id');
    }

    //Category relation
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category', 'id');
    }

    //Related Products relation
    public function relatedProducts()
    {
        return $this->hasMany(Product::class, 'product_category', 'product_category')->active();
    }

    public function setProduct3dImageAttribute($image)
    {
        if (gettype($image) != 'string') {
            $i = $image->store('images/products', 'public');
            $this->attributes['product_3d_image'] = $image->hashName();
        } else {
            $this->attributes['product_3d_image'] = $image;
        }
    }

    public function getProduct3dImageAttribute($image)
    {
        return asset('storage/images/products') . '/' . $image;
    }

    public function setProductMainImageAttribute($image)
    {
        if (gettype($image) != 'string') {
            $i = $image->store('images/products', 'public');
            $this->attributes['product_main_image'] = $image->hashName();
        } else {
            $this->attributes['product_main_image'] = $image;
        }
    }

    public function getProductMainImageAttribute($image)
    {
        $product_main_image = $this->images->where('is_main', '1')->first();
        if ($product_main_image) {
            return $product_main_image->image;
        }
        return null;
    }

    public function getName()
    {
        if (app()->getLocale() == 'ar')
            return $this->product_name_ar;
        else
            return $this->product_name_en;
    }

    public function getProductDescriptionAttribute()
    {
        $lang = session('lang');

        if ($lang == 'ar') {
            return $this->attributes['product_description_ar'];
        } else {
            return $this->attributes['product_description_en'];
        }
    }

    public function getProductNameAttribute()
    {
        $lang = session('lang');

        if ($lang == 'ar') {
            return $this->attributes['product_name_ar'];
        } else {
            return $this->attributes['product_name_en'];
        }
    }

    public function getRelatedModelProductsAttribute()
    {
        $products = $this->relatedProducts->where('product_type', $this->product_type)->where('store_id', $this->store_id);
        if ($products->count() > 4) {
            return $products->random(4);
        } else {
            return $products->random($products->count());
        }
    }

    public function scopeNameFilter(Builder $builder, array $filters = [])
    {
        $filters = array_merge([], $filters);

        if (session('lang') == 'ar') {
            $builder->when($filters !== [], function ($query) use ($filters) {
                $query
                    ->where('product_name_ar', 'like', '%' . $filters['search'] . '%');
            });
        } else {

            $builder->when($filters !== [], function ($query) use ($filters) {
                $query->where('product_name_en', 'like', '%' . $filters['search'] . '%');
            });
        }
    }

    public function scopePriceFilter(Builder $builder, array $filters = [])
    {
        $filters = array_merge([], $filters);

        $builder->when($filters !== [], function ($query) use ($filters) {
            $query->whereBetween('product_price', [$filters['from'], $filters['to']]);
        });
    }

    public function scopeMainCategoriesProducts($query, array $filters = [])
    {
        $query->whereIn('product_category', $filters);
    }

    public function scopeLanguage($query)
    {
        return $query->select($this->getLangFields());
    }

    public function scopeLang($query, $filters = [])
    {
        if ($filters['category_id'] == '0') {

            $main_categoies_ids = ProductCategory::all()->pluck('id')->toArray();
            return $query->select($this->getLangFields())
                ->where('is_delete', '0')
                ->where('product_status', 'active')
                ->whereIn('product_category', $main_categoies_ids)
                ->priceFilter($filters);
        }

        return $query->select($this->getLangFields())
            ->where('is_delete', '0')
            ->where('product_status', 'active')
            ->where('product_category', $filters['category_id'])
            ->priceFilter($filters);
    }
}
