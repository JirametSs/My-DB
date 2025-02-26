<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    private string $title = 'Categories';

    public function filterByTerm(Builder $query, ?string $term): Builder
    {
        if (!empty($term)) {
            foreach (\preg_split('/\s+/', \trim($term)) as $word) {
                $query->where(function (Builder $innerQuery) use ($word) {
                    $innerQuery
                        ->where('code', 'LIKE', "%{$word}%")
                        ->orWhere('name', 'LIKE', "%{$word}%");
                });
            }
        }
        return $query;
    }

    public function list(Request $request): View
    {
        $searchTerm = $request->input('term', '');

        $query = Category::select('categories.*')
            ->selectRaw('(SELECT COUNT(*) FROM products WHERE products.category_id = categories.id) as products_count')
            ->orderBy('code');

        if (!empty($searchTerm)) {
            $query->where('code', 'LIKE', "%{$searchTerm}%")
                ->orWhere('name', 'LIKE', "%{$searchTerm}%");
        }

        $categories = $query->paginate(5);

        return view('categories.list', [
            'title' => 'Category List',
            'search' => ['term' => $searchTerm],
            'categories' => $categories,
        ]);
    }

    public function show(string $categoryCode): View
    {
        $category = $this->findCategory($categoryCode);

        return view('categories.view', [
            'title' => "{$this->title} : View",
            'category' => $category,
        ]);
    }

    public function showProducts(Request $request, string $categoryCode): View
    {
        $category = $this->findCategory($categoryCode);
        $params = $request->all();

        $query = $category->products()->orderBy('code', 'asc');

        if (!empty($params['term'])) {
            $query->where('code', 'like', '%' . $params['term'] . '%')
                ->orWhere('name', 'like', '%' . $params['term'] . '%');
        }

        $products = $query->paginate(5);

        return view('categories.view-products', [
            'title' => "{$this->title} : Products",
            'category' => $category,
            'products' => $products,
            'search' => $params,
        ]);
    }

    public function showCreateForm(): View
    {
        return view('categories.create-form', [
            'title' => "{$this->title} : Create Category",
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|string|unique:categories,code',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255', // Add description here
            ]);

            $category = Category::create($validatedData);

            return redirect()
                ->route('categories.list')
                ->with('status', "Category {$category->code} was created successfully.");
        } catch (QueryException $ex) {
            return redirect()->back()->withInput()->withErrors([
                'error' => 'There was an issue creating the category: ' . $ex->getMessage(),
            ]);
        }
    }

    public function addProduct(Request $request, string $categoryCode): RedirectResponse
    {
        $category = Category::where('code', $categoryCode)->firstOrFail();

        try {
            $productCode = $request->input('product_code');
            $product = Product::where('code', $productCode)->firstOrFail();

            $product->category_id = $category->id;
            $product->save();

            return redirect()
                ->route('products.list')
                ->with('status', "Product {$product->code} was added.");
        } catch (QueryException $ex) {
            return redirect()->back()->withErrors([
                'error' => 'There was an issue adding the product: ' . $ex->getMessage(),
            ]);
        }
    }

    public function showAddProductsForm(Request $request, string $categoryCode): View
    {
        $category = Category::where('code', $categoryCode)->firstOrFail();
        $search = $request->input('term', '');
        $products = Product::where('name', 'LIKE', '%' . $search . '%')->get();

        return view('categories.add-products-form', [
            'category' => $category,
            'products' => $products,
            'search' => ['term' => $search],
            'title' => "Add Products to " . $category->name,
        ]);
    }

    protected function findCategory(string $categoryCode): Category
    {
        return Category::where('code', $categoryCode)->firstOrFail();
    }

    public function delete(string $categoryCode): RedirectResponse
    {
        $category = $this->findCategory($categoryCode);

        Gate::authorize('delete', $category);

        try {
            $category->delete();
            $redirectUrl = session()->get('bookmark.categories.view', route('categories.list'));

            return redirect($redirectUrl)
                ->with('success', "Category {$category->code} was deleted successfully.");
        } catch (QueryException $ex) {
            return redirect()->back()->withErrors([
                'error' => 'There was an issue deleting the category: ' . $ex->getMessage(),
            ]);
        }
    }

    public function showUpdateForm(string $categoryCode): View
    {
        $category = $this->findCategory($categoryCode);

        return view('categories.update-form', [
            'title' => "{$this->title} : Update Category",
            'category' => $category,
        ]);
    }

    public function update(Request $request, string $categoryCode): RedirectResponse
    {
        $category = $this->findCategory($categoryCode);

        try {
            $validatedData = $request->validate([
                'code' => 'required|string|max:255|unique:categories,code,' . $category->id,
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
            ]);

            $category->update($validatedData);

            return redirect()
                ->route('categories.list')
                ->with('status', "Category {$category->code} was updated successfully.");
        } catch (QueryException $ex) {
            return redirect()->back()->withErrors([
                'error' => 'There was an issue updating the category: ' . $ex->getMessage(),
            ]);
        }
    }
}