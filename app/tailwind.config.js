/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.{php, js}",
    "./views/**/*.{php,js}"
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          red: "#963a0b",
          green: "#7cae1f",
          yellow: "#d2933a"
        }
      },
      fontFamily: {
        inter: ['inter', 'sans-serif'],
        ubuntumono: ['ubuntu-mono', 'mono']
      }
    },
  },
  plugins: [],
}

