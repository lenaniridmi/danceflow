/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            boxShadow: {
                'dark': 'var(--shadow-dark)',
            },
            fontFamily: {
                sans: ['Instrument Sans', ...require('tailwindcss/defaultTheme').fontFamily.sans],
                mono: ['JetBrains Mono', ...require('tailwindcss/defaultTheme').fontFamily.mono],
            },
            colors: {
                'noble-purple': '#4B0082', /* Основной темный фиолетовый */
                'noble-purple-light': '#8A2BE2', /* Светлый фиолетовый для градиента */
                'noble-purple-hover': '#6A0DAD', /* Hover для основного цвета */
                'noble-purple-light-hover': '#9B4DCA', /* Hover для светлого цвета */
                'background-light': '#F5F5FA', /* Светлый фон */
                'background-dark': '#1A1A2E', /* Темный фон */
            },
            scale: {
                '102': '1.02', // Добавляем пользовательский класс scale-102
            },
        },
    },
    plugins: [],
};