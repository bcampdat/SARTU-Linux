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
                    'rojo': '#4c2882',//malva
                    'rojo-oscuro': '#3e2761', //malva oscuro
                    'marron': '#828282', //gris perla
                    'gris-oscuro': '#686868',
                    'negro': '#262222',
                }
            }
        },
    },

    plugins: [forms],
};
