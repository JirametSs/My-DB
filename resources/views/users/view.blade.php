<!-- resources/views/users/view.blade.php -->

@extends('layouts.main')

@section('title', $title)

@section('content')

<main>

<div class="user-actions">
        @can('update', $user)
            <a href="{{ route('users.update-form', ['user' => $user->id]) }}" class="btn btn-primary">Edit</a>
        @endcan

        <a href="{{ route('users.list') }}" class="btn btn-secondary">&lt; Back to Users List</a>
    </div><br>

    <!-- Back to User List -->
    
        
    

    <h1>User Details</h1>

    <!-- Display User Information -->
    <div class="user-info">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
    </div>

    <!-- Actions: Update or Delete User (only visible if the user has permissions) -->
    
</main>

@endsection