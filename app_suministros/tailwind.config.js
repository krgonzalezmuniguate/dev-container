/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './index.html', // Para tu archivo HTML principal
    './src/**/*.{vue,js,ts,jsx,tsx}' // Escanea todos los archivos Vue, JS, TS, JSX, TSX dentro de la carpeta src/
  ],
  theme: {
    extend: {
      zIndex: {
        100: '100'
      }
    }
  },
  plugins: []
}
