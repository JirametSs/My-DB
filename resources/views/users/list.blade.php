@extends('layouts.main')

@section('title', $title)

@section('content')
<main>
    <form action="{{ route('users.list') }}" method="get" class="search-form">
        <label>
            Search ::
            <input type="text" name="term" value="{{ $search['term'] }}" placeholder="Search Here" />
        </label><br><br>

        <button type="submit" class="button">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.85-3.85zm-5.242 1.656a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
        </svg>
            Search
        </button>
        <a href="{{ route('users.list') }}">
            <button type="button" class="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"></path>
                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"></path>
            </svg>
                Clear
            </button>
        </a><br><br>

        @can('create', App\Models\User::class)
            <nav>
                <a href="{{ route('users.create-form') }}" class="new-product-link">New User</a>
            </nav>
        @endcan

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <a href="{{ route('users.view', ['user' => $user->id]) }}">
                                {{ $user->email }}
                            </a>
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->role }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">{{ $users->links('pagination::semantic-ui') }}</div>
    </form>
</main>
@endsection