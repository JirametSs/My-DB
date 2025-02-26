<!-- resources/views/users/self.blade.php -->
@extends('layouts.main')

@section('title', 'My Profile')

@section('content')
    <nav>
        <a href="{{ route('products.list', ['user' => $user->code]) }}" class="back-link">
            &lt; Back
        </a>

        <a href="{{ route('users.update-form', ['user' => Auth::user()->id]) }}" class="back-link">
            Update Self
        </a>
    </nav>
    <form action="{{ route('users.self') }}" method="POST">
        @csrf
        
        <style>
            /* General body styling */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
            }

            /* Center the form and give it a cleaner look */
            form {
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                max-width: 400px;
                margin: 50px auto;
                display: flex;
                flex-direction: column;
            }

            /* Style for the form labels */
            form label {
                font-size: 14px;
                margin-bottom: 5px;
                color: #555;
            }

            /* Style for the input fields */
            form input[type="text"],
            form input[type="email"],
            form input[type="password"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 14px;
                box-sizing: border-box;
            }

            /* Submit button styling */
            form button {
                padding: 10px 20px;
                background-color: #28a745;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            }

            form button:hover {
                background-color: #218838;
            }

            /* Style for error messages or any feedback messages */
            .app-cmp-notification {
                background-color: #dff0d8;
                color: #3c763d;
                padding: 10px;
                margin-bottom: 20px;
                border-radius: 5px;
                text-align: center;
                font-size: 14px;
            }

            /* Add spacing to each form group */
            form div {
                margin-bottom: 15px;
            }
        </style>
<center>
    <p>
        <b>Email ::</b>
            <span>{{ $user->email }}</span><br />
        <b>Name ::</b>
            <span>{{ $user->name }}</span><br />
    </p>
</center>
        
    </form>
@endsection