<?php
namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Branch::class);

        $query = Branch::withCount('users', 'sales', 'purchases');

        // Non-super-admin only sees their own branch
        if (!auth()->user()->isSuperAdmin()) {
            $query->where('id', auth()->user()->branch_id);
        }

        $branches = $query->orderBy('name')->paginate(20);

        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        $this->authorize('create', Branch::class);
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Branch::class);

        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'code'    => 'required|string|max:20|unique:branches,code',
            'address' => 'nullable|string',
            'phone'   => 'nullable|string|max:40',
            'email'   => 'nullable|email|max:150',
            'is_active' => 'boolean',
        ]);

        Branch::create($data);

        return redirect()->route('branches.index')->with('success', 'Branch created.');
    }

    public function edit(Branch $branch)
    {
        $this->authorize('update', $branch);
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $this->authorize('update', $branch);

        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'code'    => "required|string|max:20|unique:branches,code,{$branch->id}",
            'address' => 'nullable|string',
            'phone'   => 'nullable|string|max:40',
            'email'   => 'nullable|email|max:150',
            'is_active' => 'boolean',
        ]);

        $branch->update($data);

        return redirect()->route('branches.index')->with('success', 'Branch updated.');
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);

        if ($branch->users()->exists()) {
            return back()->with('error', 'Cannot delete a branch with users assigned.');
        }
        if ($branch->code === 'MAIN') {
            return back()->with('error', 'Cannot delete the main branch.');
        }

        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted.');
    }
}