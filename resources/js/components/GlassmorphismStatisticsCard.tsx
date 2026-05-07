'use client';

import { Card } from './ui/card';
import { motion } from 'framer-motion';
import { Award, Heart, TrendingUp } from 'lucide-react';
import { useState } from 'react';
import { cn } from '../lib/utils';

interface StatData {
  label: string;
  value: string;
  icon: React.ComponentType<{ className?: string }>;
}

interface GlassmorphismStatisticsCardProps {
  image?: string;
  title?: string;
  subtitle?: string;
  description?: string;
  stats?: StatData[];
  backgroundColor?: string;
  overlayOpacity?: number;
}

const defaultStats: StatData[] = [
  { label: 'Total Raised', value: '$50,240', icon: Heart },
  { label: 'Active Campaigns', value: '12', icon: Award },
  { label: 'Growth', value: '24.5%', icon: TrendingUp },
];

const defaultImage =
  'https://images.unsplash.com/photo-1488521787991-3bc02228340c?w=800&q=80';

export function GlassmorphismStatisticsCard({
  image = defaultImage,
  title = 'Help Others',
  subtitle = 'Make a Difference',
  description = 'Join our community and support causes that matter to you',
  stats = defaultStats,
  backgroundColor = 'from-primary-900/40 via-primary-900/20 to-primary-900/40',
  overlayOpacity = 0.75,
}: GlassmorphismStatisticsCardProps) {
  const [isImageLoaded, setIsImageLoaded] = useState(false);
  const [isHovered, setIsHovered] = useState(false);

  return (
    <motion.div
      initial={{ opacity: 0, y: 30 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="w-full max-w-md"
    >
      <motion.div
        onHoverStart={() => setIsHovered(true)}
        onHoverEnd={() => setIsHovered(false)}
        initial="default"
        animate={isHovered ? 'hover' : 'default'}
      >
        <Card
          className={cn(
            'group relative h-96 overflow-hidden border transition-all duration-500 sm:h-[500px]',
            'border-white/20 dark:border-secondary-700/40 bg-white/10 dark:bg-secondary-900/10',
            'hover:border-white/40 dark:hover:border-secondary-600/60 hover:shadow-2xl',
            'backdrop-blur-xl'
          )}
          role="article"
          aria-label={`${title} - ${subtitle}`}
        >
          {/* Background Image with loading state */}
          <div className="absolute inset-0">
            {!isImageLoaded && (
              <div className="absolute inset-0 animate-pulse bg-primary-200 dark:bg-secondary-700" />
            )}
            <motion.div
              className="h-full w-full"
              variants={{
                default: { scale: 1 },
                hover: { scale: 1.05 },
              }}
              transition={{ duration: 0.6 }}
            >
              <img
                src={image}
                alt={title}
                className="h-full w-full object-cover"
                onLoad={() => setIsImageLoaded(true)}
              />
            </motion.div>
            <motion.div
              className={cn(
                'absolute inset-0',
                'bg-gradient-to-t',
                backgroundColor,
                'from-secondary-900/95 via-primary-900/50 to-transparent'
              )}
              animate={{
                opacity: isHovered ? overlayOpacity + 0.15 : overlayOpacity,
              }}
              transition={{ duration: 0.4 }}
            />
          </div>

          {/* Content Container */}
          <div className="relative flex h-full flex-col justify-between">
            {/* Empty top space */}
            <motion.div
              className="relative z-10 flex-1"
              variants={{
                default: { opacity: 0 },
                hover: { opacity: 1 },
              }}
              transition={{ duration: 0.4 }}
            />

            {/* Text content at bottom */}
            <motion.div
              className="relative z-10 p-4 sm:p-6"
              variants={{
                default: { y: 0, opacity: 1 },
                hover: { y: -12, opacity: 0.7 },
              }}
              transition={{ duration: 0.4, ease: 'easeOut' }}
            >
              <div className="space-y-1 sm:space-y-2">
                <h3 className="text-2xl font-bold text-white sm:text-3xl drop-shadow-lg">
                  {title}
                </h3>
                <p className="text-xs font-medium text-gray-100 sm:text-sm drop-shadow-md">
                  {subtitle}
                </p>
                <p className="line-clamp-2 text-xs text-gray-200/80 sm:text-sm drop-shadow-md">
                  {description}
                </p>
              </div>
            </motion.div>

            {/* Stats grid - appears on hover */}
            <motion.div
              className={cn(
                'relative z-20 border-t',
                'border-white/10 dark:border-secondary-700/20',
                'backdrop-blur-md bg-white/5 dark:bg-secondary-900/20'
              )}
              variants={{
                default: { opacity: 0, height: 0 },
                hover: { opacity: 1, height: 'auto' },
              }}
              transition={{ duration: 0.4, ease: 'easeOut' }}
            >
              <div className="grid grid-cols-3 divide-x divide-white/10 dark:divide-secondary-700/20">
                {stats.map((stat, index) => {
                  const Icon = stat.icon;
                  return (
                    <motion.div
                      key={stat.label}
                      className="flex flex-col items-center justify-center p-3 sm:p-4"
                      variants={{
                        default: { opacity: 0, y: 8 },
                        hover: { opacity: 1, y: 0 },
                      }}
                      transition={{
                        duration: 0.3,
                        delay: index * 0.06,
                        ease: 'easeOut',
                      }}
                      whileHover={{ scale: 1.02 }}
                    >
                      <Icon className="mb-2 h-4 w-4 text-gray-200 sm:h-5 sm:w-5 drop-shadow-md" />
                      <p className="text-xs text-gray-300/70 drop-shadow-sm">
                        {stat.label}
                      </p>
                      <p className="mt-1 text-sm font-bold text-white sm:text-base drop-shadow-md">
                        {stat.value}
                      </p>
                    </motion.div>
                  );
                })}
              </div>
            </motion.div>
          </div>
        </Card>
      </motion.div>
    </motion.div>
  );
}
