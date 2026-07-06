<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $shop = Shop::query()->whereHas('users', fn ($q) => $q->where('users.id', $request->user()->id))->first();

        if (! $shop) {
            $shop = Shop::query()->where('created_by', $request->user()->id)->first();
        }

        if (! $shop) {
            return redirect()->route('shop.register')->with('status', 'Create your shop profile first.');
        }

        $activeCatalogues = $shop->catalogues()
            ->where('status', 'published')
            ->whereDate('valid_from', '<=', now())
            ->whereDate('valid_to', '>=', now())
            ->count();
        $draftCatalogues = $shop->catalogues()->where('status', 'draft')->count();
        $expiredCatalogues = $shop->catalogues()->where(function ($query) {
            $query->where('status', 'expired')->orWhereDate('valid_to', '<', now());
        })->count();
        $branches = $shop->branches()->count();

        return view('shop.dashboard', compact('shop', 'activeCatalogues', 'draftCatalogues', 'expiredCatalogues', 'branches'));
    }
}
