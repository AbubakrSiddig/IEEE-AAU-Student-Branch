/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        ieee: {
          navy: '#002855',
          blue: '#00629B',
          light: '#0084B4',
          dark: '#001833',
          surface: '#F4F7FB',
          card: '#FFFFFF',
          accent: '#0A5C99',
          sidebar: '#0B192C',
        }
      },
      fontFamily: {
        sans: ['Inter', 'Cairo', 'system-ui', '-apple-system', 'sans-serif'],
      }
    },
  },
  plugins: [],
}
