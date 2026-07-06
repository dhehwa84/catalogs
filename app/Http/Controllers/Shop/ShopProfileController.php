<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopProfileController extends Controller
{
    public function edit(Request $request)
    {
        $shop = $this->resolveShop($request);

        return view('shop.profile', compact('shop'));
    }

    public function update(Request $request)
    {
        $shop = $this->resolveShop($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'trading_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'website_url' => ['nullable', 'url'],
            'facebook_url' => ['nullable', 'url'],
            'status' => ['nullable', 'in:pending,active,suspended,rejected,archived'],
        ]);

        $data['slug'] = Str::slug($data['name']);
        $shop->update($data);

        return redirect()->route('shop.profile.edit')->with('status', 'Shop profile updated.');
    }

    protected function resolveShop(Request $request): Shop
    {
        $shop = Shop::query()->whereHas('users', fn ($q) => $q->where('users.id', $request->user()->id))->first();

        if (! $shop) {
            $shop = Shop::query()->where('created_by', $request->user()->id)->firstOrFail();
        }

        return $shop;
    }
}
