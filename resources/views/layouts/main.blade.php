<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" />
</head> 
<body>
    <center>
        <header id="app-cmp-main-header">
            <h1>@section('title-container')@yield('title')@show</h1>
            
            <div class="topnav">
                <a href="{{ route('products.list') }}">Product</a>
                <a href="{{ route('categories.list') }}">Category</a>
                <a href="{{ route('shops.list') }}">Shop</a>
                @can('viewAny', \App\Models\User::class)
                    <a href="{{ route('users.list') }}">User</a>
                @endcan
            </div><br>

            <!-- User panel for authenticated users -->
            @auth
            <nav class="app-cmp-user-panel">
            <a href="{{ route('users.self') }}">{{ \Auth::user()->name }}</a>
                <a href="{{ route('logout') }}">Logout</a>
            </nav>
            @endauth
        </header>

        @session('status')
        <div class="app-cmp-notification">
            <span class="app-cl-info">{{ $value }}</span>
        </div>
        @endsession

        @if($errors->any())
        <div class="app-cmp-notification">
            <span class="app-cl-warn">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </span>
        </div>
        @endif
        
        <div id="app-cmp-main-content">
            @yield('content')
        </div>

        <footer id="app-cmp-main-footer">
            Week-07: Jiramet's Database.
        </footer>
    </center>
</body>
</html>