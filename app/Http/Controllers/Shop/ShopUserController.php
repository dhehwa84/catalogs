<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ShopUserController extends Controller
{
    public function index(Request $request)
    {
        $shop = $this->resolveShop($request);
        $users = $shop->users()->get();

        return view('shop.users.index', compact('shop', 'users'));
    }

    public function create(Request $request)
    {
        $shop = $this->resolveShop($request);

        return view('shop.users.create', compact('shop'));
    }

    public function store(Request $request)
    {
        $shop = $this->resolveShop($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'position' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:shop_admin,catalogue_manager,branch_manager,viewer'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
        ]);

        $shop->users()->attach($user->id, ['position' => $data['position'] ?? null, 'role' => $data['role'], 'is_owner' => false]);

        return redirect()->route('shop.users.index')->with('status', 'User invited.');
    }

    public function edit(Request $request, User $user)
    {
        $shop = $this->resolveShop($request);
        abort_unless($shop->users()->whereKey($user->id)->exists(), 404);
        $user = $shop->users()->whereKey($user->id)->firstOrFail();

        return view('shop.users.edit', compact('shop', 'user'));
    }

    public function update(Request $request, User $user)
    {
        $shop = $this->resolveShop($request);
        abort_unless($shop->users()->whereKey($user->id)->exists(), 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'min:8'],
            'position' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:shop_admin,catalogue_manager,branch_manager,viewer'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => filled($data['password'] ?? null) ? Hash::make($data['password']) : $user->password,
        ]);

        $shop->users()->updateExistingPivot($user->id, [
            'position' => $data['position'] ?? null,
            'role' => $data['role'],
        ]);

        return redirect()->route('shop.users.index')->with('status', 'User updated.');
    }

    public function destroy(Request $request, User $user)
    {
        $shop = $this->resolveShop($request);
        abort_unless($shop->users()->whereKey($user->id)->exists(), 404);

        $pivot = $shop->users()->whereKey($user->id)->first()?->pivot;
        abort_if($pivot?->is_owner, 422, 'Shop owners cannot be removed from this screen.');

        $shop->users()->detach($user->id);

        return redirect()->route('shop.users.index')->with('status', 'User removed from shop.');
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
