@extends('layouts.main')

@section('title', $title)

@section('content')
<main>
    {{-- Navigation --}}
    <nav>
        
            
                <a href="{{ route('products.view-shops', ['product' => $product->code]) }}" class="back-link">Show Shop</a>
            

            {{-- Update link, visible only to users who can update products --}}
            @can('update', \App\Models\Product::class)
            
                <a href="{{ route('products.update-form', ['product' => $product->code]) }}" class="back-link">Update</a>
            
            @endcan

            {{-- Delete link, visible only to users who can delete products --}}
            @can('delete', \App\Models\Product::class)
            
                <a href="{{ route('products.delete', ['product' => $product->code]) }}" class="back-link">Delete</a>
            
            @endcan

            
                <a href="{{ session()->get('bookmark.products.view', route('products.list')) }}" class="back-link">&lt; Back</a>
            
        
    </nav>

    <p>
        <b>Code ::</b>
        <span>{{ $product->code }}</span><br />
        <b>Name ::</b>
        <span>{{ $product->name }}</span><br />
        <b>Category ::</b>
        <span>{{ $product->category ? $product->category->code . ' - ' . $product->category->name : 'No Category' }}</span><br />
        <b>Price ::</b>
        <span>{{ number_format((double)$product->price, 2) }}</span><br />
    </p>
    
    <span>{{ $product->description }}</span>
</main>
@endsection