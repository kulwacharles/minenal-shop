<?php
namespace App\Policies;

use App\Models\{Sale, User};

class SalePolicy
{
    public function viewAny(User $user): bool { return $user->can('sales.view'); }
    public function view(User $user, Sale $sale): bool
    {
        return $user->can('sales.view')
            && ($user->isSuperAdmin() || $user->branch_id === $sale->branch_id);
    }
    public function create(User $user): bool { return $user->can('sales.create'); }
    public function refund(User $user, Sale $sale): bool
    {
        return $user->can('sales.refund')
            && ($user->isSuperAdmin() || $user->branch_id === $sale->branch_id);
    }
}