@extends('layouts.main')

@section('title', $title)

@section('content')

<main>

    <form action="{{ route('shops.update', ['shop' => $shop->code,]) }}" method="post">
        @csrf

        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $shop->code) }}" required>
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $shop->name) }}" required>
        </div>

        <div class="form-group">
            <label for="owner">Owner</label>
            <input type="text" id="owner" name="owner" class="form-control" value="{{ old('owner', $shop->owner) }}" required>
        </div>

        <div class="form-group">
            <label for="latitude">Latitude</label>
            <input type="text" id="latitude" name="latitude" class="form-control" value="{{ old('latitude', $shop->latitude) }}" required>
        </div>

        <div class="form-group">
            <label for="longitude">Longitude</label>
            <input type="text" id="longitude" name="longitude" class="form-control" value="{{ old('longitude', $shop->longitude) }}" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" class="form-control" required>{{ old('address', $shop->address) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</main>

@endsection