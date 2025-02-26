<?php
namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isAdministrator() || $user->id === $model->id;
    }

    public function update(User $user, User $model): bool
    {
        return $user->isAdministrator() || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isAdministrator() && $user->id !== $model->id;
    }
    
    public function updateRole(USER $user, User $targetUser, string $newRole): bool
{
    // Only allow admins to update another user's role
    if (!$user->isAdministrator()) {
        return false; // Only administrators can update roles
    }

    // List of valid roles
    $validRoles = ['ADMIN', 'USER', 'EDITOR'];

    // Check if the new role is valid
    if (in_array($newRole, $validRoles)) {
        // Update the target user's role
        return $targetUser->update(['role' => $newRole]);
    }

    return false; // Invalid role
}
}