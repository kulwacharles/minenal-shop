@php
  $user = auth()->user();
  $activeBranch = current_branch();
  $allBranches = $user?->isSuperAdmin()
      ? \App\Models\Branch::active()->orderBy('name')->get()
      : collect();
@endphp

<header class="topbar">
  <button class="icon-btn d-lg-none" id="sidebarToggle" aria-label="Toggle menu">
    <i class="bi bi-list fs-5"></i>
  </button>

  <form action="{{ route('minerals.index') }}" method="GET" class="search">
    <div class="search-wrap">
      <i class="bi bi-search"></i>
      <input type="search" name="q" value="{{ request('q') }}"
             class="form-control form-control-sm"
             placeholder="Search minerals…">
    </div>
  </form>

  @if ($user?->isSuperAdmin())
    <form method="POST" action="{{ route('switch.branch') }}" class="d-none d-md-block">
      @csrf
      <select name="branch_id" class="form-select form-select-sm"
              onchange="this.form.submit()" style="min-width:180px">
        <option value="">All branches</option>
        @foreach ($allBranches as $b)
          <option value="{{ $b->id }}" @selected(current_branch_id() === $b->id)>
            {{ $b->name }}
          </option>
        @endforeach
      </select>
    </form>
  @elseif ($activeBranch)
    <span class="badge badge-soft-info d-none d-md-inline-flex align-items-center gap-1">
      <i class="bi bi-building"></i> {{ $activeBranch->name }}
    </span>
  @endif

  <div class="ms-auto d-flex align-items-center gap-1">
    {{-- Theme toggle --}}
    <button class="icon-btn" id="themeToggle" title="Toggle theme" aria-label="Toggle theme">
      <i class="bi bi-moon-stars fs-5"></i>
    </button>

    {{-- User dropdown --}}
    <div class="dropdown">
      <button class="btn btn-link text-decoration-none d-flex align-items-center gap-2 px-2"
              data-bs-toggle="dropdown" aria-label="User menu">
        <span class="d-none d-md-inline small fw-semibold">
          {{ $user->name ?? 'Guest' }}
          <div class="small" style="color:var(--app-muted); font-weight:400">
            {{ $user?->getRoleNames()->first() ?? 'no role' }}
          </div>
        </span>
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'Guest') }}&background=d4a24c&color=fff&size=64"
             class="rounded-circle" width="34" height="34" alt="Avatar">
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li>
          <a class="dropdown-item" href="{{ route('profile.edit') }}">
            <i class="bi bi-person me-2"></i>Profile
          </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="dropdown-item text-danger" type="submit">
              <i class="bi bi-box-arrow-right me-2"></i>Log out
            </button>
          </form>
        </li>
      </ul>
    </div>

    {{-- Always-visible logout --}}
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
      @csrf
      <button class="icon-btn text-danger" type="submit" title="Log out" aria-label="Log out">
        <i class="bi bi-box-arrow-right fs-5"></i>
      </button>
    </form>
  </div>
</header>