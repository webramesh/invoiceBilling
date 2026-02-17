import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                "primary": "#2492a8",
                "danger": "#BF1E2D",
                "background-light": "#f9fafa",
                "background-dark": "#16181d",
                "accent-emerald": "#10b981",
                "accent-red": "#ef4444",
            },
            fontFamily: {
                sans: ['Geist', ...defaultTheme.fontFamily.sans],
                display: ['Geist', 'sans-serif'],
            },
            borderRadius: {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
            },
        },
    },

    plugins: [forms],
};
