
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
      colors: {
        "ruby": {
            "50": "#ff5a87",
            "100": "#ff507d",
            "200": "#ff4673",
            "300": "#ff3c69",
            "400": "#ff325f",
            "500": "#ff2855",
            "600": "#f51e4b",
            "700": "#eb1441",
            "800": "#e10a37",
            "900": "#d7002d"
          },
          'orange': {
            '50': '#fff6ed',
            '100': '#ffebd4',
            '200': '#ffd2a8',
            '300': '#ffb270',
            '400': '#ff8637',
            '500': '#ff6008',
            '600': '#f04a06',
            '700': '#c73507',
            '800': '#9e2a0e',
            '900': '#7f260f',
        },

      }

    }
  },

  plugins: [
    require('@tailwindcss/typography'),
  ],

}
