<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

// Home route - redirect to the products list as the default home page
Route::get('/', function () {
    return redirect()->route('products.list');
});

// Wrap authentication routes and controller routes with cache control middleware
Route::middleware([
    'cache.headers:no_store;no_cache;must_revalidate;max_age=0',
])->group(function () {
    // Authentication routes
    Route::controller(LoginController::class)
        ->prefix('auth')
        ->group(function() {
            // Name this route to login by default setting
            Route::get('/login', 'showLoginForm')->name('login');
            Route::post('/login', 'authenticate')->name('authenticate');
            Route::get('/logout', 'logout')->name('logout');
        });

    // Authenticated routes
    Route::middleware(['auth'])->group(function() {

        // Product routes
        Route::controller(ProductController::class)
            ->prefix('products')
            ->name('products.')
            ->group(function () {
                Route::get('', 'list')->name('list');
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('/create', 'create')->name('create');

                Route::prefix('/{product}')->group(function () {
                    Route::get('', 'show')->name('view');
                    Route::get('/update', 'showUpdateForm')->name('update-form');
                    Route::put('/update', 'update')->name('update'); // Change POST to PUT
                    Route::delete('/delete', 'delete')->name('delete');
                    Route::get('/shops', 'showShops')->name('view-shops');

                    Route::prefix('/shops')->group(function() {
                        Route::get('/add', 'showAddShopsForm')->name('add-shops-form');
                        Route::post('/add', 'addShop')->name('add-shop');
                        Route::delete('/{shop}/remove', 'removeShop')->name('remove-shop');
                    });
                });
            });

        // Shop routes
        Route::controller(ShopController::class)
            ->prefix('shops')
            ->name('shops.')
            ->group(function() {
                Route::get('', 'list')->name('list');
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('/create', 'create')->name('create');

                Route::prefix('/{shop}')->group(function () {
                    Route::get('', 'show')->name('view');
                    Route::get('/update', 'showUpdateForm')->name('update-form');
                    Route::put('/update', 'update')->name('update'); // Change POST to PUT
                    Route::delete('/delete', 'delete')->name('delete');
                    Route::get('/products', 'showProducts')->name('view-products');

                    Route::prefix('/products')->group(function() {
                        Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                        Route::post('/add', 'addProduct')->name('add-product');
                        Route::delete('/{product}/remove', 'removeProduct')->name('remove-product');
                    });
                });
            });

        // Category routes
        Route::controller(CategoryController::class)
            ->prefix('categories')
            ->name('categories.')
            ->group(function() {
                Route::get('', 'list')->name('list');
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('/create', 'create')->name('create');

                Route::prefix('/{category}')->group(function () {
                    Route::get('', 'show')->name('view');
                    Route::get('/update', 'showUpdateForm')->name('update-form');
                    Route::put('/update', 'update')->name('update'); // Change POST to PUT
                    Route::delete('/delete', 'delete')->name('delete');
                    Route::get('/products', 'showProducts')->name('view-products');

                    Route::prefix('/products')->group(function() {
                        Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                        Route::post('/add', 'addProduct')->name('add-product');
                    });
                });
                
            });

        // User routes with authorization middleware
        Route::controller(UserController::class)
            ->prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('/self', 'showSelf')->name('self');
        Route::post('/self', 'selfUpdate')->name('self.update');
        Route::get('', 'list')->name('list'); // User list
        Route::get('/create', 'showCreateForm')->name('create-form');
        Route::post('/create', 'create')->name('create');
        Route::get('/{user}', 'show')->name('view');
        Route::get('/{user}/update', 'showUpdateForm')->name('update-form');
        Route::post('/{user}/update', 'update')->name('update');
        Route::get('/{user}/delete', 'delete')->name('delete');
            });
    });
});