<?php
namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('users.view');
    }

    public function create(User $user): bool
    {
        return $user->can('users.create');
    }

    public function update(User $user, User $target): bool
    {
        if (!$user->can('users.update')) return false;
        // Can't edit a super-admin unless you're a super-admin
        if ($target->hasRole('super-admin') && !$user->hasRole('super-admin')) return false;
        // Can't edit someone from another branch (unless super-admin)
        if (!$user->isSuperAdmin() && $user->branch_id !== $target->branch_id) return false;
        return true;
    }

    public function delete(User $user, User $target): bool
    {
        if (!$user->can('users.delete')) return false;
        if ($user->id === $target->id) return false; // can't delete self
        if ($target->hasRole('super-admin') && !$user->hasRole('super-admin')) return false;
        if (!$user->isSuperAdmin() && $user->branch_id !== $target->branch_id) return false;
        return true;
    }
}