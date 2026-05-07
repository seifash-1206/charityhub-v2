import './bootstrap';
import Alpine from 'alpinejs';
import React from 'react';
import ReactDOM from 'react-dom/client';
import { ThemeProvider } from './providers/ThemeProvider';

// Start Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize React on mount-points if they exist
const mountPoint = document.getElementById('react-root');
if (mountPoint) {
  const root = ReactDOM.createRoot(mountPoint);
  root.render(
    <React.StrictMode>
      <ThemeProvider>
        <div className="min-h-screen bg-background dark:bg-secondary-900 text-foreground dark:text-white" />
      </ThemeProvider>
    </React.StrictMode>
  );
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', () => {
  const storedTheme = localStorage.getItem('theme');
  const systemPreference = window.matchMedia('(prefers-color-scheme: dark)').matches
    ? 'dark'
    : 'light';

  const theme = storedTheme || systemPreference;
  const html = document.documentElement;

  if (theme === 'dark') {
    html.classList.add('dark');
  } else {
    html.classList.remove('dark');
  }
});

// Export components for use in Blade
export { Navigation } from './components/Navigation';
export { HeroScroll } from './components/HeroScroll';
export { GlassmorphismStatisticsCard } from './components/GlassmorphismStatisticsCard';
export { ThemeProvider } from './providers/ThemeProvider';
