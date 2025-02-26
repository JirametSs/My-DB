@extends('layouts.main')

@section('title', $title)

@section('content')

<center>

<h1>Create</h1>

<main class="container">
    
    <form action="{{ route('categories.create') }}" method="post" class="form">
    @csrf
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" name="code" id="code" class="form-control" />
        </div><br>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" />
        </div><br>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" cols="80" rows="5" class="form-control"></textarea>
        </div><br>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</main>
</center>

@endsection