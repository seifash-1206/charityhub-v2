@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-primary-900 dark:text-secondary-300']) }}>
    {{ $value ?? $slot }}
</label>
