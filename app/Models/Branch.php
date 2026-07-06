<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function suburb()
    {
        return $this->belongsTo(Suburb::class);
    }

    public function operatingHours()
    {
        return $this->hasMany(BranchOperatingHour::class);
    }

    public function catalogues()
    {
        return $this->belongsToMany(Catalogue::class, 'catalogue_branch')->withTimestamps();
    }
}
