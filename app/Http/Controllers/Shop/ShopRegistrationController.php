<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopRegistrationController extends Controller
{
    public function create(Request $request)
    {
        $categories = ShopCategory::query()->orderBy('name')->get();

        return view('shop.register', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'trading_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'shop_category_id' => ['nullable', 'exists:shop_categories,id'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
        ]);

        $shop = Shop::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'trading_name' => $data['trading_name'] ?? null,
            'description' => $data['description'] ?? null,
            'shop_category_id' => $data['shop_category_id'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'whatsapp' => $data['whatsapp'] ?? null,
            'created_by' => $request->user()->id,
            'status' => 'pending',
        ]);

        $shop->users()->attach($request->user()->id, ['role' => 'shop_owner', 'is_owner' => true]);

        return redirect()->route('shop.dashboard')->with('status', 'Shop registered. It is awaiting approval.');
    }
}
