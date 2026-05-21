import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './documentation/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                lime: '#C8F135',
                navy: {
                    DEFAULT: '#0D1117',
                    2: '#131920',
                },
                ink: '#1E2A38',
                soft: '#F0F4FF',
                accent: '#FF6B35',
                sky: '#38BDF8',
                card: '#192130',
                card2: '#1E2A3A',
                muted: '#4B6080',
            },
        },
    },

    plugins: [forms],
};