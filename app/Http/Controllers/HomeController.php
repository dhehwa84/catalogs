<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\Shop;

class HomeController extends Controller
{
    public function index()
    {
        $catalogues = Catalogue::query()
            ->with(['shop', 'category'])
            ->publicVisible()
            ->latestPromotions()
            ->limit(8)
            ->get();

        $shops = Shop::query()->active()->latest()->limit(6)->get();

        return view('home', compact('catalogues', 'shops'));
    }
}
