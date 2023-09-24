
/** @type {import('tailwindcss').Config} */

module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/js/**/*.js',

    ],
    safelist: [
        'grid-cols-1',
        'grid-cols-2',
        'grid-cols-3',
        'grid-cols-4',
        'col-span-1',
        'col-span-2',
        'col-span-3',
        'col-span-4',

    ],
    theme: {

        extend: {
            animation: {
                marquee: "marquee 50s linear infinite"
            },
            keyframes: {
                marquee: {
                    from: {
                        transform: 'translateX(0)',
                    },
                    to: {
                        transform: 'translateX(calc(-100% - 2.5rem))',
                    },
                },
            },

            colors: {
                'lochmara': {
                    '50': '#f0f9ff',
                    '100': '#e5f1f9',
                    '200': '#b9e4fe',
                    '300': '#7ccffd',
                    '400': '#38b9fa',
                    '500': '#00b0eb',
                    '600': '#007bc4',
                    '700': '#0164a3',
                    '800': '#065586',
                    '900': '#0b476f',
                    '950': '#072d4a',
                },

                'citron': {
                    '50': '#fafde8',
                    '100': '#f1facd',
                    '200': '#e2f4a2',
                    '300': '#cceb6b',
                    '400': '#b5dd3e',
                    '500': '#95c11f',
                    '600': '#759b15',
                    '700': '#587615',
                    '800': '#475e16',
                    '900': '#3d5017',
                    '950': '#1f2c07',
                },


            }

        }
    },

    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
    ],

}
