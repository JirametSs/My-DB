@extends('layouts.main')

@section('title', $title)

@section('content')

<main>
    <h1>Create a New User</h1>

    <form action="{{ route('users.create') }}" method="POST">
        @csrf

        <!-- Name Input -->
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <div class="error text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Input -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            @error('email')
                <div class="error text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Input -->
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
            @error('password')
                <div class="error text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password Input -->
        <div class="form-group">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            @error('password_confirmation')
                <div class="error text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Role Selection -->
        <div class="form-group">
            <label for="role">Role:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="USER" {{ old('role') == 'USER' ? 'selected' : '' }}>USER</option>
                <option value="ADMIN" {{ old('role') == 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
            </select>
            @error('role')
                <div class="error text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</main>

@endsection