/** @type {import('tailwindcss').Config} */

module.exports = {
  content: [                               //CONFIGURE CORRECTLY
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/**/*.js',
    './vendor/secondnetwork/kompass/**/*.blade.php',    
  ],  

  safelist: [
    'grid-cols-1',
    'grid-cols-2',
    'grid-cols-3',
    'grid-cols-4',
    'grid-cols-5',
    'col-span-1',
    'col-span-2',
    'col-span-3',
    'col-span-4',
    'col-span-5',
    
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: "#E6F0FF",
          100: "#CCE0FF",
          200: "#9FC5FE",
          300: "#6CA6FE",
          400: "#3F8BFD",
          500: "#0D6EFD",
          600: "#0256D4",
          700: "#013F9D",
          800: "#012B6A",
          900: "#001433"
        },
        secondary: {
          50: "#ECEDEF",
          100: "#D8DBDE",
          200: "#B4BAC0",
          300: "#8D96A0",
          400: "#69737D",
          500: "#495057",
          600: "#3A4045",
          700: "#2C3035",
          800: "#1E2124",
          900: "#0E0F11"
        },
        brand: {
          50: "#FFF6E5",
          100: "#FFEDCC",
          200: "#FFDB99",
          300: "#FFC966",
          400: "#FFB833",
          500: "#FFA700",
          600: "#CC8500",
          700: "#996300",
          800: "#664200",
          900: "#332100"
        }
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    // require('@tailwindcss/forms'), 
    require('@tailwindcss/typography'),
    require('@tailwindcss/container-queries'), 
],
}
