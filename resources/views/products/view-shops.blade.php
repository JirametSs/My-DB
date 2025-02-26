@extends('layouts.main')

@section('title', $title)

@section('content')

<main>
    {{-- Navigation --}}
    <nav>
        <a href="{{ route('products.view', ['product' => $product->code]) }}" class="button">
            &lt; Back
        </a>
    </nav>
    <br><br>

    <form action="{{ route('products.view-shops', ['product' => $product->code]) }}" method="get" class="search-form">
        <label>
            Search ::
            <input type="text" name="term" value="{{ $search['term'] ?? '' }}" placeholder="Search Here"/>
        </label>
        <br><br>

        <button type="submit" class="button">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.85-3.85zm-5.242 1.656a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
            </svg>
            Search
        </button>

        <a href="{{ route('products.view-shops', ['product' => $product->code]) }}">
            <button type="button" class="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                    <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                    <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                </svg>
                Clear
            </button>
        </a>
    </form>
    <br>

    <table class="styled-table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Owner</th>
                {{-- Actions column, only visible to authorized users --}}
                @can('update', \App\Models\Product::class)
                <th>Actions</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @php
                session()->put('bookmark.products.view', url()->full());
            @endphp
            @foreach($shops as $shop)
                <tr>
                    <td>
                        <a href="{{ route('shops.view', ['shop' => $shop->code]) }}">
                            {{ $shop->code }}
                        </a>
                    </td>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->owner }}</td>

                    {{-- Add and Remove actions, only visible to authorized users --}}
                    @can('update', \App\Models\Product::class)
                    <td>
                        <a href="{{ route('products.add-shops-form', ['product' => $product->code]) }}" class="button">
                            Add
                        </a>
                        <a href="{{ route('products.remove-shop', ['product' => $product->code, 'shop' => $shop->code]) }}" class="button">
                            Remove
                        </a>
                    </td>
                    @endcan
                </tr>
            @endforeach
        </tbody>
    </table>
</main>

@endsection