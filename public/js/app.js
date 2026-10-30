/* ============================================================
   Mobile sidebar
   ============================================================ */
(function () {
  const sidebar  = document.getElementById('sidebar');
  const backdrop = document.getElementById('backdrop');
  const toggle   = document.getElementById('sidebarToggle');
  if (!sidebar || !backdrop) return;

  if (toggle) {
    toggle.addEventListener('click', () => {
      sidebar.classList.toggle('show');
      backdrop.classList.toggle('show');
    });
  }
  backdrop.addEventListener('click', () => {
    sidebar.classList.remove('show');
    backdrop.classList.remove('show');
  });
})();

/* ============================================================
   Theme engine
   ============================================================ */
const MineralTheme = {
  charts: [],

  current() {
    return document.documentElement.getAttribute('data-bs-theme') || 'light';
  },
  isDark() {
    return this.current() === 'dark';
  },
  grid() {
    return this.isDark() ? '#233040' : '#eef1f5';
  },
  tick() {
    return this.isDark() ? '#8494a8' : '#7a8798';
  },

  apply(theme, persist = true) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    if (persist) {
      try { localStorage.setItem('theme', theme); } catch (e) {}
    }
    const icon = document.querySelector('#themeToggle i');
    if (icon) {
      icon.className = theme === 'dark' ? 'bi bi-sun fs-5' : 'bi bi-moon-stars fs-5';
    }
    this.sync();
  },

  register(chart) {
    this.charts.push(chart);
    this.sync([chart]);
  },

  sync(list) {
    const targets = list || this.charts;
    targets.forEach(c => {
      try {
        c.options.scales.y.grid.color  = this.grid();
        c.options.scales.y.ticks.color = this.tick();
        c.options.scales.x.ticks.color = this.tick();
        c.update('none');
      } catch (_) { /* chart may not have scales (pie/doughnut) */ }
    });
  }
};
window.MineralTheme = MineralTheme;

/* ---------- Sync icon on page load ---------- */
(function () {
  const icon = document.querySelector('#themeToggle i');
  if (!icon) return;
  icon.className = MineralTheme.isDark() ? 'bi bi-sun fs-5' : 'bi bi-moon-stars fs-5';
})();

/* ---------- Toggle button ---------- */
const themeBtn = document.getElementById('themeToggle');
if (themeBtn) {
  themeBtn.addEventListener('click', () => {
    MineralTheme.apply(MineralTheme.isDark() ? 'light' : 'dark');
  });
}

/* ---------- Follow system preference (if no explicit choice) ---------- */
if (window.matchMedia) {
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    let stored = null;
    try { stored = localStorage.getItem('theme'); } catch (err) {}
    if (!stored) {
      MineralTheme.apply(e.matches ? 'dark' : 'light', false);
    }
  });
}