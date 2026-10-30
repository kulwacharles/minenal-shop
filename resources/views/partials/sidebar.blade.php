@php
    $user = auth()->user();
    $branchId = current_branch_id();

    // Branch-scoped low-stock count
    $lowStockQuery = \App\Models\Mineral::lowStock();
    if (!$user->isSuperAdmin() && $user->branch_id) {
        // Count minerals where this branch's stock <= min_qty
        $lowCount = \App\Models\BranchStock::where('branch_id', $user->branch_id)
            ->whereColumn('quantity', '<=', 'min_qty')
            ->count();
    } elseif ($user->isSuperAdmin() && $branchId) {
        $lowCount = \App\Models\BranchStock::where('branch_id', $branchId)
            ->whereColumn('quantity', '<=', 'min_qty')
            ->count();
    } else {
        $lowCount = $lowStockQuery->count();
    }
@endphp

<aside class="sidebar" id="sidebar">
  <a href="{{ route('dashboard') }}" class="brand">
    <i class="bi bi-gem"></i> MineralHub
  </a>

  <nav class="py-2">
    <div class="nav-section">Main</div>
    <a href="{{ route('dashboard') }}"
       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <i class="bi bi-grid-1x2"></i> Dashboard
    </a>

    {{-- ============ INVENTORY ============ --}}
    @canany(['minerals.view', 'categories.view', 'stock.view'])
      <div class="nav-section">Inventory</div>

      @can('minerals.view')
        <a href="{{ route('minerals.index') }}"
           class="nav-link {{ request()->routeIs('minerals.*') ? 'active' : '' }}">
          <i class="bi bi-gem"></i> Minerals
        </a>
      @endcan

      @can('categories.view')
        <a href="{{ route('categories.index') }}"
           class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
          <i class="bi bi-tags"></i> Categories
        </a>
      @endcan

      @can('stock.view')
        <a href="{{ route('stock.index') }}"
           class="nav-link {{ request()->routeIs('stock.*') ? 'active' : '' }}">
          <i class="bi bi-box-seam"></i> Stock
          @if ($lowCount > 0)
            <span class="badge rounded-pill text-bg-danger">{{ $lowCount }}</span>
          @endif
        </a>
      @endcan
    @endcanany

    {{-- ============ SALES ============ --}}
    @canany(['sales.view', 'customers.view'])
      <div class="nav-section">Sales</div>

      @can('sales.view')
        <a href="{{ route('sales.index') }}"
           class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
          <i class="bi bi-receipt"></i> Sales / POS
        </a>
      @endcan

      @can('customers.view')
        <a href="{{ route('customers.index') }}"
           class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
          <i class="bi bi-people"></i> Customers
        </a>
      @endcan
    @endcanany

    {{-- ============ PROCUREMENT ============ --}}
    @canany(['suppliers.view', 'purchases.view'])
      <div class="nav-section">Procurement</div>

      @can('suppliers.view')
        <a href="{{ route('suppliers.index') }}"
           class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
          <i class="bi bi-truck"></i> Suppliers
        </a>
      @endcan

      @can('purchases.view')
        <a href="{{ route('purchases.index') }}"
           class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
          <i class="bi bi-bag-check"></i> Purchases
        </a>
      @endcan
    @endcanany

    {{-- ============ ADMINISTRATION ============ --}}
    @canany(['users.view', 'branches.view'])
      <div class="nav-section">Administration</div>

      @can('users.view')
        <a href="{{ route('users.index') }}"
           class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
          <i class="bi bi-people-fill"></i> Users
        </a>
      @endcan

      @can('branches.view')
        <a href="{{ route('branches.index') }}"
           class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
          <i class="bi bi-building"></i> Branches
        </a>
      @endcan
    @endcanany

    {{-- ============ SYSTEM ============ --}}
    @can('reports.view')
      <div class="nav-section">System</div>
      <a href="{{ route('reports.index') }}"
         class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart"></i> Reports
      </a>
    @endcan
  </nav>
</aside>