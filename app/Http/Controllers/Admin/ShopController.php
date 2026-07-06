<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403);
        }

        $shops = Shop::query()->latest()->paginate(20);

        return view('admin.shops.index', compact('shops'));
    }

    public function show(Request $request, Shop $shop)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403);
        }

        return view('admin.shops.show', compact('shop'));
    }

    public function approve(Request $request, Shop $shop)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403);
        }

        $shop->update(['status' => 'active', 'approved_at' => now(), 'approved_by' => $request->user()->id]);

        return back()->with('status', 'Shop approved.');
    }

    public function reject(Request $request, Shop $shop)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403);
        }

        $shop->update(['status' => 'rejected']);

        return back()->with('status', 'Shop rejected.');
    }

    public function suspend(Request $request, Shop $shop)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403);
        }

        $shop->update(['status' => 'suspended']);

        return back()->with('status', 'Shop suspended.');
    }
}
