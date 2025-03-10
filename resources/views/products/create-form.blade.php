@extends('layouts.main')

@section('title', $title)

@section('content')

    <div class="container">
        <h1 class="page-title">Create New Product</h1>

        <form action="{{ route('products.create-form') }}" method="post" class="create-form">
            @csrf

            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" required>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }} " required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category_id">
                    <option value="" >-- Please select a category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                            [{{ $category->code }}] {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" step="any" name="price" value="{{ old('price') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5"  required class="price-input">{{ old('description') }}</textarea>
            </div>

                <button type="submit" class="btn btn-primary">Create Product</button>
                
        </form>
    </div>

@endsection