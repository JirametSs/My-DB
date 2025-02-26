@extends('layouts.main')

@section('title', $title)

@section('content')

<main>
    {{-- Navigation --}}
    <nav>
        
            
                <a href="{{ route('categories.view-products', ['category' => $category->code]) }}" class="back-link">Show Product</a>
            
            
            
                <a href="{{ route('categories.update-form', ['category' => $category->code]) }}" class="back-link">Update</a>
            
            
            {{-- Delete link, only visible to authorized users who can delete this category --}}
            @can('delete', $category)
            
                <a href="{{ route('categories.delete', ['category' => $category->code]) }}" class="back-link">Delete</a>
            
            @endcan

            
                <a href="{{ session()->get('bookmark.categories.view', route('categories.list')) }}" class="back-link">&lt; Back</a>
            
        
    </nav>

    <p>
        <b>Code ::</b>
        <span>{{ $category->code }}</span><br /><br>
        <b>Name ::</b>
        <span>{{ $category->name }}</span><br /><br>
        <b>Description ::</b>
        <span>{{ $category->description }}</span>
    </p>
</main>

@endsection