@extends('layouts.main')

@section('title', $title)

@section('content')

<main>

<nav>
    <a href="{{ route('shops.view-products', [
        'shop' => $shop->code,
    ]) }}" class="back-link">Show Product</a>

    @can('update', $shop)
        <a href="{{ route('shops.update-form', [
            'shop' => $shop->code,
        ]) }}" class="back-link">Update</a>
    @endcan

    @can('delete', $shop)
        <a href="{{ route('shops.delete', [
            'shop' => $shop->code,
        ]) }}" class="back-link">Delete</a>
    @endcan

    <a href="{{
        session()->get('bookmark.products.view', route('shops.list'))
    }}" class="back-link">&lt; Back</a>
</nav>

<p>
    <b>Code ::</b>
        <span>{{ $shop->code }}</span><br />
    <b>Name ::</b>
        <span>{{ $shop->name }}</span><br />
    <b>Owner ::</b>
        <span>{{ $shop->owner }}</span><br />
    <b>Location ::</b>
        <span>{{ number_format((double)$shop->latitude, 7) }} ,{{ number_format((double)$shop->longitude, 7) }}</span><br />

    <b>Address ::</b>
        {{ $shop->address }}
</p>

</main>

@endsection