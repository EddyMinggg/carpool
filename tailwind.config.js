/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  output: './public/css/tailwind.css',
  darkMode: 'class'
}