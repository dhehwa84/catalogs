<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\CatalogueCategory;
use App\Models\City;
use App\Models\Shop;
use App\Models\Suburb;
use Illuminate\Http\Request;

class CatalogueBrowseController extends Controller
{
    public function index(Request $request)
    {
        $query = Catalogue::query()->with(['shop', 'category', 'branches.city', 'branches.suburb']);

        $query->where('status', 'published')
            ->whereHas('shop', fn ($shop) => $shop->where('status', 'active'));

        if ($request->boolean('expired_only')) {
            $query->expired();
        } elseif (! $request->boolean('show_expired') && ! $request->filled('valid_from') && ! $request->filled('valid_to')) {
            $query->whereDate('valid_from', '<=', now())
                ->whereDate('valid_to', '>=', now());
        }

        if ($request->filled('q')) {
            $query->where(function ($inner) use ($request) {
                $inner->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('description', 'like', '%'.$request->q.'%')
                    ->orWhereHas('shop', fn ($shop) => $shop->where('name', 'like', '%'.$request->q.'%'));
            });
        }

        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        if ($request->filled('catalogue_category_id')) {
            $query->where('catalogue_category_id', $request->catalogue_category_id);
        }

        if ($request->filled('city_id')) {
            $query->whereHas('branches', fn ($branch) => $branch->where('city_id', $request->city_id));
        }

        if ($request->filled('suburb_id')) {
            $query->where(function ($inner) use ($request) {
                $inner->whereHas('branches', fn ($branch) => $branch->where('suburb_id', $request->suburb_id))
                    ->orWhereHas('areas', fn ($area) => $area->where('suburbs.id', $request->suburb_id));
            });
        }

        if ($request->filled('valid_from')) {
            $query->whereDate('valid_from', '>=', $request->valid_from);
        }

        if ($request->filled('valid_to')) {
            $query->whereDate('valid_to', '<=', $request->valid_to);
        }

        $catalogues = $query->latestPromotions()->paginate(12)->withQueryString();
        $shops = Shop::query()->active()->orderBy('name')->get();
        $categories = CatalogueCategory::query()->orderBy('name')->get();
        $cities = City::query()->orderBy('name')->get();
        $suburbs = Suburb::query()->orderBy('name')->get();

        return view('catalogues.index', compact('catalogues', 'shops', 'categories', 'cities', 'suburbs'));
    }
}
