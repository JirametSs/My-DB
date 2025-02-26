<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


class UserController extends Controller
{
    private string $title = 'Users';

    // List users with search functionality and pagination
    public function list(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $searchTerm = $request->input('term', '');

        $query = User::query()->orderBy('email');
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('role', 'LIKE', "%{$searchTerm}%");
            });
        }

        $users = $query->paginate(10);

        return view('users.list', [
            'title' => "{$this->title} List",
            'search' => ['term' => $searchTerm],
            'users' => $users,
        ]);
    }

    // Show a specific user's details
    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        return view('users.view', [
            'title' => "{$this->title} : View User",
            'user' => $user,
        ]);
    }

    // Show the form to create a new user
    public function showCreateForm(): View
    {
        Gate::authorize('create', User::class);

        return view('users.create-form', [
            'title' => "{$this->title} : Create User",
        ]);
    }

    // Handle the creation of a new user
    public function create(Request $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
            'role' => 'required|string|in:ADMIN,USER',
        ]);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        return redirect()->route('users.list')->with('success', 'User created successfully.');
    }

    // Show the form to update an existing user
    public function showUpdateForm(User $user): View
    {
        Gate::authorize('update', $user);

        return view('users.update-form', [
            'title' => "{$this->title} : Update User",
            'user' => $user,
        ]);
    }

    // Handle updating a user
    public function update(Request $request, User $user): RedirectResponse
{
    Gate::authorize('update', $user);

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:4|confirmed',
        'role' => 'required|string|in:ADMIN,USER', // Changed to uppercase ADMIN and USER
    ]);

    $user->update([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => !empty($validatedData['password']) ? bcrypt($validatedData['password']) : $user->password,
        'role' => $validatedData['role'],
    ]);

    return redirect()->route('users.list')->with('success', 'User updated successfully.');
}

    // Handle deleting a user
    public function delete(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.list')->with('success', 'User deleted successfully.');
    }

    // Show the form to update the currently authenticated user
    public function showSelf(): View
    {
        $user = Auth::user(); // Get the currently authenticated user
        return view('users.self', [
            'title' => "My Profile",
            'user' => $user,
        ]);
    }

    // Handle updating the currently authenticated user
    public function selfUpdate(Request $request): RedirectResponse
{
    $user = Auth::user(); // Get the authenticated user

    // Validate the request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Ensure email uniqueness except for the current user
        'password' => 'nullable|string|min:4|confirmed', // Password is optional
    ]);

    // Only update the password if it was provided
    if (!empty($validatedData['password'])) {
        $user->password = bcrypt($validatedData['password']);
    }

    // Update the user's name and email
    $user->name = $validatedData['name'];
    $user->email = $validatedData['email'];

    // Save the updated user information
    

    return redirect()->route('users.self')->with('status', 'Profile updated successfully.');
}
}
