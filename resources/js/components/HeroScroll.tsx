'use client';

import { motion } from 'framer-motion';
import React, { useEffect, useState } from 'react';

interface HeroScrollProps {
  image: string;
  title: string;
  subtitle?: string;
  children?: React.ReactNode;
  height?: string;
}

export function HeroScroll({
  image,
  title,
  subtitle,
  children,
  height = 'h-96',
}: HeroScrollProps) {
  const [scrollY, setScrollY] = useState(0);

  useEffect(() => {
    const handleScroll = () => {
      setScrollY(window.scrollY);
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <div className={`relative w-full ${height} overflow-hidden`}>
      {/* Parallax background image */}
      <motion.div
        className="absolute inset-0 w-full h-full"
        style={{
          backgroundImage: `url(${image})`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          y: scrollY * 0.5,
        }}
      />

      {/* Dark overlay gradient */}
      <div className="absolute inset-0 bg-gradient-to-b from-primary-900/60 via-primary-900/70 to-primary-900/80 dark:from-secondary-900/80 dark:via-secondary-900/85 dark:to-secondary-900/90" />

      {/* Content overlay */}
      <div className="absolute inset-0 flex items-center justify-center">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8 }}
          className="text-center text-white px-4 max-w-2xl"
        >
          <h1 className="text-4xl md:text-5xl font-bold mb-4 drop-shadow-lg">
            {title}
          </h1>
          {subtitle && (
            <p className="text-lg md:text-xl text-gray-100 drop-shadow-md mb-6">
              {subtitle}
            </p>
          )}
          {children}
        </motion.div>
      </div>
    </div>
  );
}
