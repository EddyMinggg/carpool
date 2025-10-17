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
          darker: '#164e63',
          darkest: '#083344',
          accent: "#3db1d1ff",
          "opaque": "#0891b23d",
          "opaque-dark": "#0e74906c",
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