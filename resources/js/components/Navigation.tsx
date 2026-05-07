'use client';

import { Menu, Moon, Sun, X } from 'lucide-react';
import { useEffect, useState } from 'react';
import { cn } from '../lib/utils';
import { Button } from './ui/button';

interface NavigationProps {
  logo?: string;
  logoText?: string;
  links?: Array<{
    label: string;
    href: string;
  }>;
  showThemeToggle?: boolean;
}

export function Navigation({
  logo,
  logoText = 'CharityHub',
  links = [
    { label: 'Campaigns', href: '/campaigns' },
    { label: 'Donations', href: '/donations' },
    { label: 'Volunteers', href: '/volunteers' },
  ],
  showThemeToggle = true,
}: NavigationProps) {
  const [mounted, setMounted] = useState(false);
  const [isDark, setIsDark] = useState(false);
  const [isOpen, setIsOpen] = useState(false);

  useEffect(() => {
    setMounted(true);
    setIsDark(document.documentElement.classList.contains('dark'));
  }, []);

  const toggleTheme = () => {
    const html = document.documentElement;
    if (isDark) {
      html.classList.remove('dark');
      localStorage.setItem('theme', 'light');
      setIsDark(false);
    } else {
      html.classList.add('dark');
      localStorage.setItem('theme', 'dark');
      setIsDark(true);
    }
  };

  if (!mounted) {
    return null;
  }

  return (
    <nav
      className={cn(
        'sticky top-0 z-40 w-full',
        'bg-white/80 dark:bg-secondary-900/80',
        'backdrop-blur-xl border-b',
        'border-secondary-200 dark:border-secondary-800'
      )}
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          {/* Logo */}
          <div className="flex items-center gap-2">
            {logo && (
              <img src={logo} alt="Logo" className="h-8 w-8" />
            )}
            <a
              href="/"
              className={cn(
                'font-bold text-xl',
                'text-primary-900 dark:text-white',
                'hover:text-primary-800 dark:hover:text-primary-200',
                'transition-colors'
              )}
            >
              {logoText}
            </a>
          </div>

          {/* Desktop Navigation */}
          <div className="hidden md:flex items-center gap-8">
            {links.map((link) => (
              <a
                key={link.href}
                href={link.href}
                className={cn(
                  'relative px-3 py-2 text-sm font-medium',
                  'text-secondary-700 dark:text-secondary-300',
                  'hover:text-primary-900 dark:hover:text-primary-300',
                  'transition-colors',
                  'after:absolute after:bottom-0 after:left-0 after:h-0.5',
                  'after:w-0 after:bg-gradient-to-r after:from-primary-900 after:to-primary-700',
                  'after:transition-all after:duration-300',
                  'hover:after:w-full'
                )}
              >
                {link.label}
              </a>
            ))}
          </div>

          {/* Right side controls */}
          <div className="flex items-center gap-4">
            {showThemeToggle && (
              <button
                onClick={toggleTheme}
                className={cn(
                  'p-2 rounded-lg',
                  'bg-primary-100 dark:bg-secondary-700',
                  'text-primary-900 dark:text-primary-300',
                  'hover:bg-primary-200 dark:hover:bg-secondary-600',
                  'transition-colors'
                )}
                aria-label="Toggle theme"
              >
                {isDark ? <Sun size={20} /> : <Moon size={20} />}
              </button>
            )}

            {/* Mobile menu button */}
            <button
              onClick={() => setIsOpen(!isOpen)}
              className="md:hidden p-2 rounded-lg hover:bg-secondary-100 dark:hover:bg-secondary-800 transition-colors"
              aria-label="Toggle menu"
            >
              {isOpen ? <X size={24} /> : <Menu size={24} />}
            </button>
          </div>
        </div>

        {/* Mobile Navigation */}
        {isOpen && (
          <div className="md:hidden pb-4 space-y-2">
            {links.map((link) => (
              <a
                key={link.href}
                href={link.href}
                className={cn(
                  'block px-3 py-2 rounded-lg',
                  'text-secondary-700 dark:text-secondary-300',
                  'hover:bg-primary-100 dark:hover:bg-secondary-800',
                  'hover:text-primary-900 dark:hover:text-primary-300',
                  'transition-colors'
                )}
              >
                {link.label}
              </a>
            ))}
          </div>
        )}
      </div>
    </nav>
  );
}
