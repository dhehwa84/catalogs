<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\Shop;

class CatalogueShowController extends Controller
{
    public function show(Shop $shop, Catalogue $catalogue)
    {
        abort_unless($catalogue->shop_id === $shop->id, 404);
        abort_unless($shop->status === 'active' && $catalogue->status === 'published', 404);

        $catalogue->load(['shop', 'category', 'branches.operatingHours', 'branches.city', 'branches.suburb', 'areas']);

        return view('catalogues.show', compact('shop', 'catalogue'));
    }
}
