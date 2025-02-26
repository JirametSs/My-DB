<?php
// app/Policies/CategoryPolicy.php
namespace App\Policies;

use App\Models\User;

class CategoryPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function update(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function delete(User $user): bool
    {
        return $user->isAdministrator();
    }
}