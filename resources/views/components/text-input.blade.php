@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 border-b-2 border-t-0 border-l-0 border-r-0 rounded-none bg-transparent px-0 py-2 focus:border-uniradical-blue focus:ring-0 transition-colors duration-200']) }}>
