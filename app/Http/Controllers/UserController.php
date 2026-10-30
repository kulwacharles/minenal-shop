<?php
namespace App\Http\Controllers;

use App\Models\{User, Branch};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, DB};
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('branch', 'roles')
            ->when(auth()->user()->isSuperAdmin() === false,
                   fn($q) => $q->where('branch_id', auth()->user()->branch_id))
            ->when(request('q'), fn($q, $t) => $q->where(function ($q) use ($t) {
                $q->where('name', 'like', "%$t%")->orWhere('email', 'like', "%$t%");
            }))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return view('users.create', $this->formData());
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $data = $this->validateUser($request);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'branch_id' => $data['branch_id'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);
            $user->syncRoles([$data['role']]);
        });

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user') + $this->formData());
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $this->validateUser($request, $user);

        DB::transaction(function () use ($data, $user) {
            $user->update([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'branch_id' => $data['branch_id'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);
            if (!empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }
            $user->syncRoles([$data['role']]);
        });

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    protected function validateUser(Request $r, ?User $user = null): array
    {
        $id = $user?->id;
        return $r->validate([
            'name'      => 'required|string|max:150',
            'email'     => "required|email|max:150|unique:users,email,{$id}",
            'password'  => $user ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'role'      => 'required|exists:roles,name',
            'branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean',
        ]);
    }

    protected function formData(): array
    {
        $roles = Role::orderBy('name')->get();

        // Non-super-admin can only create roles below theirs — keep it simple:
        // super-admin → any role; admin → manager + cashier
        if (!auth()->user()->isSuperAdmin()) {
            $roles = $roles->whereIn('name', ['manager', 'cashier'])->values();
        }

        // Non-super-admin can only pick their own branch
        $branches = auth()->user()->isSuperAdmin()
            ? Branch::active()->orderBy('name')->get()
            : Branch::where('id', auth()->user()->branch_id)->get();

        return compact('roles', 'branches');
    }
}