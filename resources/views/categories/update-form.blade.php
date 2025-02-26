@extends('layouts.main')

@section('title', $title)

@section('content')

<main>
    <form action="{{ route('categories.update', ['category' => $category->code]) }}" method="post">
        @csrf
        
        <label>Code
            <input type="text" name="code" value="{{ $category->code }}" />
        </label><br />

        <label>Name
            <input type="text" name="name" value="{{ $category->name }}" />
        </label><br />

        <label>Description
            <textarea name="description" cols="80" rows="10">{{ $category->description }}</textarea>
        </label><br />

        <button type="submit">Update</button>
    </form>
</main>

@endsection