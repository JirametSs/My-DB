<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;

class ShopController extends SearchableController
{
    private string $title = 'Shops';

    public function getQuery(): Builder
    {
        return Shop::orderBy('code');
    }

    public function filterByTerm(Builder|Relation $query, ?string $term): Builder|Relation
    {
        if (!empty($term)) {
            foreach (\preg_split('/\s+/', \trim($term)) as $word) {
                $query->where(function (Builder $innerQuery) use ($word) {
                    $innerQuery
                        ->where('code', 'LIKE', "%{$word}%")
                        ->orWhere('name', 'LIKE', "%{$word}%")
                        ->orWhere('owner', 'LIKE', "%{$word}%");
                });
            }
        }
        return $query;
    }

    public function list(Request $request): View
    {
        $searchTerm = $request->input('term', '');
        $query = $this->getQuery()->withCount('products');
        if (!empty($searchTerm)) {
            $query = $this->filterByTerm($query, $searchTerm);
        }
        $shops = $query->paginate(10);

        return view('shops.list', [
            'title' => "{$this->title} List",
            'search' => ['term' => $searchTerm],
            'shops' => $shops,
        ]);
    }

    public function show(string $shopCode): View
    {
        $shop = $this->find($shopCode);

        return view('shops.view', [
            'title' => "{$this->title} : View",
            'shop' => $shop,
        ]);
    }

    public function showProducts(Request $request, string $shopCode): View
    {
        $shop = $this->find($shopCode);
        $search = $request->query('term', '');
        $query = $shop->products()->orderBy('code', 'asc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }
        $products = $query->paginate(10);

        return view('shops.view-products', [
            'title' => "{$shop->name} Products",
            'shop' => $shop,
            'search' => ['term' => $search],
            'products' => $products,
        ]);
    }

    public function showAddProductsForm(Request $request, string $shopCode): View
    {
        $shop = Shop::where('code', $shopCode)->firstOrFail();
        Gate::authorize('update', $shop);
        $search = $request->input('term', '');
        $query = Product::orderBy('code')
            ->whereDoesntHave('shops', function ($query) use ($shop) {
                $query->where('id', $shop->id);
            });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%$search%")
                    ->orWhere('name', 'LIKE', "%$search%");
            });
        }
        $products = $query->paginate(5);

        return view('shops.add-products-form', [
            'title' => "Add Products to {$shop->name}",
            'shop' => $shop,
            'products' => $products,
            'search' => ['term' => $search],
        ]);
    }

    public function addProduct(Request $request, string $shopCode): RedirectResponse
    {
        $shop = Shop::where('code', $shopCode)->firstOrFail();
        Gate::authorize('update', $shop);

        try {
            $productCode = $request->input('product_code');
            $product = Product::where('code', $productCode)->firstOrFail();

            if (!$shop->products()->where('products.id', $product->id)->exists()) {
                $shop->products()->attach($product);
            }

            return redirect()
                ->route('products.list')
                ->with('status', "Product {$product->code} was added.");
        } catch (QueryException $ex) {
            return redirect()->back()->withErrors([
                'error' => 'There was an issue adding the product: ' . $ex->getMessage(),
            ]);
        }
    }

    public function removeProduct(string $shopCode, string $productCode): RedirectResponse
    {
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop);

        try {
            $product = $shop->products()->where('code', $productCode)->first();

            if ($product) {
                $shop->products()->detach($product->id);

                return redirect()
                    ->route('products.list')
                    ->with('status', "Product {$product->code} was removed.");
            }

            return redirect()->back()->with('error', 'Product not found in this shop');
        } catch (QueryException $ex) {
            return redirect()->back()->withErrors([
                'error' => 'There was an issue removing the product: ' . $ex->getMessage(),
            ]);
        }
    }

    public function showUpdateForm(string $shopCode): View
    {
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop);

        return view('shops.update-form', [
            'title' => "{$this->title} : Update Shop",
            'shop' => $shop,
        ]);
    }

    public function update(Request $request, string $shopCode): RedirectResponse
    {
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop);

        try {
            $validatedData = $request->validate([
                'code' => 'required|string|max:255|unique:shops,code,' . $shop->id,
                'name' => 'required|string|max:255',
                'owner' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            $shop->update($validatedData);

            return redirect()
                ->route('shops.list')
                ->with('status', "Shop {$shop->code} was updated.");
        } catch (QueryException $ex) {
            return redirect()->back()->withErrors([
                'error' => 'There was an issue updating the shop: ' . $ex->getMessage(),
            ]);
        }
    }

    public function create(Request $request): RedirectResponse
    {
        Gate::authorize('create', Shop::class);

        try {
            $validated = $request->validate([
                'code' => 'required|unique:shops,code|max:255',
                'name' => 'required|max:255',
                'owner' => 'required|max:255',
                'address' => 'required|string|max:255',  
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            $shop = Shop::create($validated);

            return redirect()
                ->route('shops.list')
                ->with('status', "Shop {$shop->code} was created successfully.");
        } catch (QueryException $ex) {
            return redirect()->back()->withErrors([
                'error' => 'There was an issue creating the shop: ' . $ex->getMessage(),
            ]);
        }
    }

    function find(string $shopCode): Shop
    {
        return Shop::where('code', $shopCode)->firstOrFail();
    }

    public function delete(string $shopCode): RedirectResponse
    {
        $shop = $this->find($shopCode);
        Gate::authorize('delete', $shop);

        try {
            $shop->delete();
            $redirectUrl = session()->get('bookmark.shops.view', route('shops.list'));

            return redirect($redirectUrl)
                ->with('success', "Shop {$shop->code} was deleted successfully.");
        } catch (QueryException $ex) {
            return redirect()->back()->withErrors([
                'error' => 'There was an issue deleting the shop: ' . $ex->getMessage(),
            ]);
        }
    }
}