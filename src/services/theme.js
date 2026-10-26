// Theme management service (Light / Dark / System)
export const themeService = {
  getTheme() {
    return localStorage.getItem('ieee_theme') || 'system';
  },

  setTheme(mode) {
    localStorage.setItem('ieee_theme', mode);
    this.applyTheme(mode);
  },

  applyTheme(mode = null) {
    const current = mode || this.getTheme();
    const isDark = current === 'dark' || (current === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
    
    if (isDark) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
    return isDark;
  },

  toggle() {
    const current = this.getTheme();
    const next = current === 'light' ? 'dark' : (current === 'dark' ? 'system' : 'light');
    this.setTheme(next);
    return next;
  }
};
