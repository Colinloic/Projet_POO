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
        'primary' : '#436bb5',
      //  bg type pokemon
        'water': '#4f92d6',
        'dragon': '#2d70c2',
        'electrik': '#f5d147',
        'fight': '#ce426b',
        'bug': '#91c03b',
        'fire': '#f19b53',
        'fly': '#8faade',
        'ghost': '#526bad',
        'grass': '#63bb5a',
        'ground': '#d67744',
        'ice': '#6fc6b9',
        'normal': '#d7d7d7',
        'poison': '#aa6ec8',
        'psy': '#d1636a',
        'rock': '#c5b68c',
      },
    },
  },
  plugins: [
    require('tw-elements/dist/plugin')
  ],
}
