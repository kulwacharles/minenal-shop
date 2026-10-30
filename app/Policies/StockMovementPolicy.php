<?php
namespace App\Policies;

use App\Models\{StockMovement, User};

class StockMovementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('stock.view');
    }

    public function adjust(User $user): bool
    {
        return $user->can('stock.adjust');
    }
}