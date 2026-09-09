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
                heading: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                body: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    navy: '#071A3D',
                    'navy-dark': '#040F24',
                    'navy-light': '#0D2654',
                    blue: '#0857C3',
                    orange: '#E4432A',
                    green: '#0F8A5F',
                    amber: '#B8790A',
                    muted: '#5B6472',
                    border: '#E7E9EE',
                    bg: '#F8F9FC',
                },
            },
        },
    },

    plugins: [forms],
};
