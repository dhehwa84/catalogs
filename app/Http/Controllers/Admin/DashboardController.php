<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\Shop;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403);
        }

        $shops = Shop::query()->latest()->limit(5)->get();
        $catalogues = Catalogue::query()->latest()->limit(5)->get();
        $pendingShops = Shop::query()->where('status', 'pending')->count();
        $activeCatalogues = Catalogue::query()->where('status', 'published')->count();

        return view('admin.dashboard', compact('shops', 'catalogues', 'pendingShops', 'activeCatalogues'));
    }
}
