<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(ShopCategory::class, 'shop_category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'shop_user')->withPivot(['position', 'role', 'is_owner'])->withTimestamps();
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function catalogues()
    {
        return $this->hasMany(Catalogue::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
