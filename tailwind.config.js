/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
       "primary": {
          DEFAULT:  '#0891b2',
          dark: '#0e7490',
          accent: "#46caef",
        },
        "secondary": {
          DEFAULT: '#ffffff',
          dark: '#262626',
          accent: '#404040'
        },
      }
    },
  },
  output: './public/css/tailwind.css',
  darkMode: 'class',
  plugins: [
    require('@tailwindcss/forms'),
  ],
}