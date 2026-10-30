<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Http\Request;

class SetCurrentBranch
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $current = $request->session()->get('current_branch_id');

            // Default to MAIN branch if none selected yet
            if ($current === null) {
                $current = Branch::where('code', 'MAIN')->value('id');
                $request->session()->put('current_branch_id', $current);
            }

            session(['effective_branch_id' => $current]);
        } else {
            session(['effective_branch_id' => $user->branch_id]);
        }

        return $next($request);
    }
}