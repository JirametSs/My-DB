<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Styles for the login container */
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full viewport height */
    background-color: #f0f2f5; /* Light background */
    padding: 20px;
}

/* Styles for the login form */
.login-form {
    background-color: #ffffff; /* White background */
    padding: 40px; /* Spacing inside the form */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
    max-width: 400px; /* Max width of the form */
    width: 100%; /* Full width up to max-width */
}

/* Form heading */
.login-form h2 {
    text-align: center; /* Center heading text */
    margin-bottom: 20px; /* Space below heading */
    color: #333; /* Darker text color */
    font-size: 24px;
}

/* Styles for form groups */
.form-group {
    margin-bottom: 20px; /* Space between form elements */
}

/* Labels */
.form-group label {
    display: block; /* Make label a block element */
    margin-bottom: 8px; /* Space below label */
    color: #333; /* Darker text color */
    font-weight: bold; /* Bold labels */
}

/* Input fields */
.form-group input {
    width: 100%; /* Full width input */
    padding: 12px; /* Spacing inside input */
    border: 1px solid #ccc; /* Border color */
    border-radius: 4px; /* Rounded corners */
    box-sizing: border-box; /* Include padding in width */
    font-size: 16px; /* Font size */
    background-color: #f9f9f9; /* Slightly gray background */
}

/* Input focus state */
.form-group input:focus {
    border-color: #007bff; /* Border color on focus */
    outline: none; /* Remove default outline */
    box-shadow: 0 0 4px rgba(0, 123, 255, 0.2); /* Light blue shadow */
}

/* Error message */
.warn {
    color: #d9534f; /* Red color for errors */
    font-size: 14px; /* Smaller font size */
    margin-top: 10px; /* Space above the error message */
    text-align: center; /* Center align */
}

/* Login button */
.login-button {
    display: block; /* Block element */
    width: 100%; /* Full width */
    padding: 12px; /* Spacing inside button */
    background-color: #007bff; /* Primary color */
    color: white; /* White text color */
    border: none; /* Remove border */
    border-radius: 4px; /* Rounded corners */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s ease; /* Smooth transition */
}

/* Login button hover state */
.login-button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}
    </style>
</head>
<body>
    <div class="login-container">
        <form action="{{ route('authenticate') }}" method="post" class="login-form">
            @csrf
            <h2>Login</h2>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text" id="email" name="email" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>
            <button type="submit" class="login-button">Login</button>
            @error('credentials')
            <div class="warn">{{ $message }}</div>
            @enderror
            
        </form>
    </div>
    <center>
            <footer id="app-cmp-main-footer">
        @copyright Week-12 2024 : Jiramet's Database.
            </footer>
    </center>
</body>
</html>
