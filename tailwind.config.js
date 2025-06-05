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
                serif: ['Playfair Display', 'Georgia', 'serif'],
            },
            colors: {
                primary: {
                    50: '#f7f7f6',
                    100: '#e9e9e6',
                    200: '#d4d4cd',
                    300: '#b3b3a7',
                    400: '#8e8e7d',
                    500: '#737365',
                    600: '#5c5c51',
                    700: '#4b4b42',
                    800: '#3e3e37',
                    900: '#34342f',
                },
                accent: {
                    50: '#f7f4e9',
                    100: '#f0e8d1',
                    200: '#e4d3a4',
                    300: '#d8be77',
                    400: '#ccaa4a',
                    500: '#c09632',
                    600: '#a77f29',
                    700: '#8a6824',
                    800: '#705324',
                    900: '#5c4622',
                },
                luxury: {
                    gold: '#d4af37',
                    cream: '#f8f6f3',
                    dark: '#2c2c2c',
                },
            },
            backgroundImage: {
                'gradient-luxury': 'linear-gradient(135deg, #3e3e37 0%, #5c5c51 100%)',
                'gradient-accent': 'linear-gradient(135deg, #c09632 0%, #a77f29 100%)',
            },
            boxShadow: {
                'luxury': '0 10px 30px -5px rgba(52, 52, 47, 0.3)',
                'luxury-lg': '0 20px 40px -10px rgba(52, 52, 47, 0.4)',
            },
        },
    },

    plugins: [forms],
};
