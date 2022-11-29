/** @type {import('tailwindcss').Config} */
module.exports = {
  experimental: {
    optimizeUniversalDefaults: true
  },
  content: [
    './assets/**/*.js',
    './templates/**/*.html.twig',
    './node_modules/tw-elements/dist/js/**/*.js'
  ],
  theme: {   
    extend: {
      colors: {
        'yellow': '#FFCC03',
        'blue': '#386ABB',
        'dark': '#000000',
        'white': '#FFFFFF',
        'type_eau': '#399CFF',
        'primary' : '#436bb5'
      },
    },
  },
  plugins: [
    require('tw-elements/dist/plugin')
  ],
}
