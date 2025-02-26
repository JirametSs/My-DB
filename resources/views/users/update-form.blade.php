@extends('layouts.main')

@section('title', $title)

@section('content')

<main>
    <h1>Update User</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST">
        @csrf
        @method('POST')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="role">Role:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="USER" {{ old('role', $user->role) == 'USER' ? 'selected' : '' }}>USER</option>
                <option value="ADMIN" {{ old('role', $user->role) == 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
            </select>
            @error('role')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password">
            <small>Leave blank if you don't want to change the password</small>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>

        <a href="{{ route('users.list') }}" class="btn btn-secondary">Cancel</a>
    </form>
</main>

@endsection