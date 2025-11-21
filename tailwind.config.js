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
                sans: ['Montserrat', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'sartu': {
                    'rojo': '#C74D38',
                    'rojo-oscuro': '#9B4E40', 
                    'marron': '#704841',
                    'gris-oscuro': '#463734',
                    'negro': '#262222',
                }
            }
        },
    },

    plugins: [forms],
};
