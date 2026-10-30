<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Sign in') — MineralHub</title>

  <script>
    (function () {
      try {
        var stored = localStorage.getItem('theme');
        var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.setAttribute('data-bs-theme', stored || (prefersDark ? 'dark' : 'light'));
      } catch (e) { document.documentElement.setAttribute('data-bs-theme', 'light'); }
    })();
  </script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <style>
    /* ============================================================
       Auth shell — split screen
       ============================================================ */
    .auth-shell {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1fr 1fr;
    }

    /* ---------- Brand panel ---------- */
    .auth-brand {
      position: relative; overflow: hidden;
      padding: 3rem;
      display: flex; flex-direction: column; justify-content: space-between;
      color: #fff;
      background:
        radial-gradient(circle at 20% 20%, rgba(212,162,76,.20), transparent 55%),
        linear-gradient(145deg, #14202e 0%, #0a1219 100%);
    }
    .auth-brand .watermark {
      position: absolute; top: -60px; right: -60px;
      font-size: 26rem; opacity: .05; pointer-events: none;
      color: #d4a24c; transform: rotate(-15deg);
    }
    .auth-brand .brand-logo {
      display: inline-flex; align-items: center; gap: .55rem;
      color: #fff; text-decoration: none;
      font-weight: 700; font-size: 1.2rem;
    }
    .auth-brand .brand-logo i { color: #d4a24c; font-size: 1.5rem; }
    .auth-brand h2 {
      font-size: 2rem; font-weight: 700; line-height: 1.2;
      max-width: 22ch; margin: 0 0 1rem;
    }
    .auth-brand p.lead-sm {
      color: #a9b6c7; font-size: 1rem; max-width: 32ch;
      margin-bottom: 2rem;
    }
    .auth-brand ul { list-style: none; padding: 0; margin: 0; }
    .auth-brand ul li {
      display: flex; align-items: center; gap: .6rem;
      color: #cdd6e1; font-size: .92rem; padding: .35rem 0;
    }
    .auth-brand ul li i { color: #d4a24c; font-size: 1rem; }
    .auth-brand footer { font-size: .78rem; color: #5d6b7d; }

    /* ---------- Form panel ---------- */
    .auth-form-wrap {
      display: flex; align-items: center; justify-content: center;
      padding: 2rem;
      background: var(--app-bg);
      transition: background-color .2s ease;
    }
    .auth-card { width: 100%; max-width: 420px; }
    .auth-card .card { padding: 2rem; }

    .auth-card h1 {
      font-size: 1.5rem; font-weight: 700; margin: 0 0 .35rem;
      color: var(--app-text);
    }
    .auth-card .auth-subtitle {
      color: var(--app-muted); font-size: .9rem; margin-bottom: 1.75rem;
    }

    /* Form controls */
    .auth-card .form-label {
      font-size: .82rem; font-weight: 600; margin-bottom: .35rem;
      color: var(--app-text);
    }
    .auth-card .form-control {
      background: var(--app-input-bg);
      border: 1px solid var(--app-border);
      color: var(--app-text);
    }
    .auth-card .form-control:focus {
      background: var(--app-surface);
      border-color: var(--accent);
      box-shadow: 0 0 0 .2rem rgba(212,162,76,.15);
      color: var(--app-text);
    }
    .auth-card .form-control::placeholder { color: var(--app-muted); }
    .auth-card .form-check-label { color: var(--app-muted); font-size: .85rem; }
    .auth-card .form-check-input:checked {
      background-color: var(--accent); border-color: var(--accent);
    }

    .auth-card .btn-primary {
      background: #d4a24c; border-color: #d4a24c; color: #14202e;
      font-weight: 600; padding: .6rem 1rem;
    }
    .auth-card .btn-primary:hover {
      background: #c49440; border-color: #c49440; color: #14202e;
    }
    .auth-card .btn-primary:focus {
      box-shadow: 0 0 0 .2rem rgba(212,162,76,.35);
    }

    .auth-card a:not(.btn):not(.brand-logo) {
      color: var(--accent); text-decoration: none; font-weight: 500;
    }
    .auth-card a:not(.btn):hover { text-decoration: underline; }

    .auth-divider {
      display: flex; align-items: center; gap: 1rem;
      margin: 1.25rem 0; color: var(--app-muted); font-size: .82rem;
    }
    .auth-divider::before, .auth-divider::after {
      content: ''; flex: 1; height: 1px; background: var(--app-border);
    }

    .theme-toggle-floating {
      position: fixed; top: 1rem; right: 1rem; z-index: 10;
      width: 38px; height: 38px; border-radius: 50%;
      display: inline-flex; align-items: center; justify-content: center;
      background: var(--app-surface); color: var(--app-text);
      border: 1px solid var(--app-border); box-shadow: var(--app-shadow);
      transition: background .15s;
    }
    .theme-toggle-floating:hover { background: var(--app-hover); }
    #themeToggle i { transition: transform .3s ease; }
    #themeToggle:hover i { transform: rotate(20deg); }

    /* ---------- Responsive ---------- */
    @media (max-width: 991.98px) {
      .auth-shell { grid-template-columns: 1fr; }
      .auth-brand { display: none; }
      .auth-form-wrap { padding: 1.5rem; }
    }
  </style>
</head>
<body>

  <button class="theme-toggle-floating" id="themeToggle" title="Toggle theme">
    <i class="bi bi-moon-stars fs-5"></i>
  </button>

  <div class="auth-shell">

    <aside class="auth-brand">
      <i class="bi bi-gem watermark"></i>

      <a href="/" class="brand-logo"><i class="bi bi-gem"></i> MineralHub</a>

      <div>
        <h2>Welcome to your mineral empire.</h2>
        <p class="lead-sm">Manage inventory, process sales, and track your precious stones — all in one place.</p>
        <ul>
          <li><i class="bi bi-check2-circle"></i> Real-time stock tracking</li>
          <li><i class="bi bi-check2-circle"></i> Built-in point-of-sale</li>
          <li><i class="bi bi-check2-circle"></i> Beautiful sales reports</li>
        </ul>
      </div>

      <footer>© {{ date('Y') }} MineralHub. All rights reserved.</footer>
    </aside>

    <main class="auth-form-wrap">
      <div class="auth-card">
        @yield('content')
      </div>
    </main>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
    // Sync toggle icon on load (auth pages have no navbar, so this is a light duplication)
    (function () {
      const icon = document.querySelector('#themeToggle i');
      if (!icon) return;
      const dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
      icon.className = dark ? 'bi bi-sun fs-5' : 'bi bi-moon-stars fs-5';

      document.getElementById('themeToggle').addEventListener('click', () => {
        const next = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-bs-theme', next);
        try { localStorage.setItem('theme', next); } catch (e) {}
        icon.className = next === 'dark' ? 'bi bi-sun fs-5' : 'bi bi-moon-stars fs-5';
      });
    })();
  </script>
</body>
</html>