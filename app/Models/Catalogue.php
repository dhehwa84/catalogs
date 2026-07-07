<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    protected $guarded = [];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'published_at' => 'datetime',
        'subscribers_notified_at' => 'datetime',
        'expired_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(CatalogueCategory::class, 'catalogue_category_id');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'catalogue_branch')->withTimestamps();
    }

    public function areas()
    {
        return $this->belongsToMany(Suburb::class, 'catalogue_area')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published')
            ->whereDate('valid_from', '<=', now())
            ->whereDate('valid_to', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'expired')
                ->orWhereDate('valid_to', '<', now());
        });
    }

    public function scopePublicVisible($query)
    {
        return $query->active()->whereHas('shop', fn ($q) => $q->where('status', 'active'));
    }

    public function scopeLatestPromotions($query)
    {
        return $query->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');
    }
}
