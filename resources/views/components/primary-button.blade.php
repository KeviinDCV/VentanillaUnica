<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-uniradical-blue border-none font-medium text-sm text-white rounded-md transition-all duration-200 hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-uniradical-blue focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
