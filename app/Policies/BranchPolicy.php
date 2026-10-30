<?php
namespace App\Policies;

use App\Models\{Branch, User};

class BranchPolicy
{
    public function viewAny(User $user): bool { return $user->can('branches.view'); }
    public function view(User $user, Branch $branch): bool
    {
        return $user->can('branches.view')
            && ($user->isSuperAdmin() || $user->branch_id === $branch->id);
    }
    public function create(User $user): bool { return $user->can('branches.create'); }
    public function update(User $user, Branch $branch): bool
    {
        return $user->can('branches.update')
            && ($user->isSuperAdmin() || $user->branch_id === $branch->id);
    }
    public function delete(User $user, Branch $branch): bool
    {
        return $user->can('branches.delete') && $user->isSuperAdmin();
    }
}