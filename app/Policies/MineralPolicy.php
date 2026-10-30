<?php
namespace App\Policies;

use App\Models\{Mineral, User};

class MineralPolicy
{
    public function viewAny(User $user): bool   { return $user->can('minerals.view'); }
    public function view(User $user, Mineral $m): bool { return $user->can('minerals.view'); }
    public function create(User $user): bool    { return $user->can('minerals.create'); }
    public function update(User $user, Mineral $m): bool { return $user->can('minerals.update'); }
    public function delete(User $user, Mineral $m): bool { return $user->can('minerals.delete'); }
}