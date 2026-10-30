<?php

if (!function_exists('current_branch_id')) {
    function current_branch_id(): ?int
    {
        return session('effective_branch_id');
    }
}

if (!function_exists('current_branch')) {
    function current_branch(): ?\App\Models\Branch
    {
        $id = current_branch_id();
        return $id ? \App\Models\Branch::find($id) : null;
    }
}