@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-secondary-300 dark:border-secondary-600 bg-white/80 dark:bg-secondary-700/50 text-secondary-900 dark:text-secondary-100 placeholder-secondary-400 dark:placeholder-secondary-500 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 rounded-md shadow-sm']) }}>
