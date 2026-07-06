<?php

namespace App\Http\Controllers;

use App\Models\Shop;

class ShopShowController extends Controller
{
    public function show(Shop $shop)
    {
        abort_unless($shop->status === 'active', 404);

        $shop->load(['category', 'branches.operatingHours', 'branches.city', 'branches.suburb']);
        $catalogues = $shop->catalogues()->with(['category'])->publicVisible()->latestPromotions()->get();

        return view('shops.show', compact('shop', 'catalogues'));
    }
}
