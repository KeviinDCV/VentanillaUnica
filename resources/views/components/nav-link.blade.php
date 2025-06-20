@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-uniradical-blue text-sm font-medium leading-5 text-uniradical-blue focus:outline-none transition duration-200 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-light leading-5 text-gray-600 hover:text-uniradical-blue hover:border-uniradical-blue focus:outline-none focus:text-uniradical-blue transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
