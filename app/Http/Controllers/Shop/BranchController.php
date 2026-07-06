<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\City;
use App\Models\Province;
use App\Models\Shop;
use App\Models\Suburb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $shop = $this->resolveShop($request);
        $branches = $shop->branches()->with(['province', 'city', 'suburb'])->get();
        $provinces = Province::query()->orderBy('name')->get();

        return view('shop.branches.index', compact('shop', 'branches', 'provinces'));
    }

    public function create(Request $request)
    {
        $shop = $this->resolveShop($request);
        $provinces = Province::query()->orderBy('name')->get();
        $cities = City::query()->orderBy('name')->get();
        $suburbs = Suburb::query()->orderBy('name')->get();

        return view('shop.branches.create', compact('shop', 'provinces', 'cities', 'suburbs'));
    }

    public function store(Request $request)
    {
        $shop = $this->resolveShop($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'suburb_id' => ['nullable', 'exists:suburbs,id'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_main' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'hours' => ['nullable', 'array'],
            'hours.*.opens_at' => ['nullable', 'date_format:H:i'],
            'hours.*.closes_at' => ['nullable', 'date_format:H:i'],
            'hours.*.is_closed' => ['nullable', 'boolean'],
        ]);

        $data['shop_id'] = $shop->id;
        $hours = $data['hours'] ?? [];
        unset($data['hours']);
        $data['is_main'] = $request->boolean('is_main');
        $data['is_active'] = $request->boolean('is_active', true);

        DB::transaction(function () use ($shop, $data, $hours) {
            $branch = $shop->branches()->create($data);
            $this->syncHours($branch, $hours);
        });

        return redirect()->route('shop.branches.index')->with('status', 'Branch added.');
    }

    public function show(Request $request, Branch $branch)
    {
        abort_unless($branch->shop_id === $this->resolveShop($request)->id, 404);
        $branch->load(['province', 'city', 'suburb', 'operatingHours']);

        return view('shop.branches.show', compact('branch'));
    }

    public function edit(Request $request, Branch $branch)
    {
        abort_unless($branch->shop_id === $this->resolveShop($request)->id, 404);
        $provinces = Province::query()->orderBy('name')->get();
        $cities = City::query()->orderBy('name')->get();
        $suburbs = Suburb::query()->orderBy('name')->get();
        $shop = $this->resolveShop($request);

        return view('shop.branches.edit', compact('shop', 'branch', 'provinces', 'cities', 'suburbs'));
    }

    public function update(Request $request, Branch $branch)
    {
        abort_unless($branch->shop_id === $this->resolveShop($request)->id, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'suburb_id' => ['nullable', 'exists:suburbs,id'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_main' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'hours' => ['nullable', 'array'],
            'hours.*.opens_at' => ['nullable', 'date_format:H:i'],
            'hours.*.closes_at' => ['nullable', 'date_format:H:i'],
            'hours.*.is_closed' => ['nullable', 'boolean'],
        ]);

        $hours = $data['hours'] ?? [];
        unset($data['hours']);
        $data['is_main'] = $request->boolean('is_main');
        $data['is_active'] = $request->boolean('is_active', true);

        DB::transaction(function () use ($branch, $data, $hours) {
            $branch->update($data);
            $this->syncHours($branch, $hours);
        });

        return redirect()->route('shop.branches.index')->with('status', 'Branch updated.');
    }

    public function destroy(Request $request, Branch $branch)
    {
        abort_unless($branch->shop_id === $this->resolveShop($request)->id, 404);
        $branch->delete();

        return redirect()->route('shop.branches.index')->with('status', 'Branch removed.');
    }

    protected function resolveShop(Request $request): Shop
    {
        $shop = Shop::query()->whereHas('users', fn ($q) => $q->where('users.id', $request->user()->id))->first();

        if (! $shop) {
            $shop = Shop::query()->where('created_by', $request->user()->id)->firstOrFail();
        }

        return $shop;
    }

    protected function syncHours(Branch $branch, array $hours): void
    {
        foreach (range(1, 7) as $day) {
            $dayHours = $hours[$day] ?? [];
            $branch->operatingHours()->updateOrCreate(
                ['day_of_week' => $day],
                [
                    'opens_at' => ($dayHours['is_closed'] ?? false) ? null : ($dayHours['opens_at'] ?? null),
                    'closes_at' => ($dayHours['is_closed'] ?? false) ? null : ($dayHours['closes_at'] ?? null),
                    'is_closed' => (bool) ($dayHours['is_closed'] ?? false),
                ],
            );
        }
    }
}
