@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-light text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
