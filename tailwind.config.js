import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Paleta de colores institucional para UniRadic
                'uniradical': {
                    'blue': '#082ca4',
                    'white': '#FFFFFF',
                },
                // Sobrescribir colores primarios con nuestra paleta institucional
                'primary': {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#082ca4',
                    600: '#082ca4',
                    700: '#082ca4',
                    800: '#082ca4',
                    900: '#082ca4',
                    950: '#082ca4',
                },
            },
        },
    },

    plugins: [forms],
};
