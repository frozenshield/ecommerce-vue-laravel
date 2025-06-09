import type { Config } from 'tailwindcss'

const config: Config = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
    "./node_modules/primevue/**/*.{vue,js,ts,jsx,tsx}"
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: 'var(--primary-500)',
          50: 'var(--primary-50)',
          100: 'var(--primary-100)',
          200: 'var(--primary-200)',
          300: 'var(--primary-300)',
          400: 'var(--primary-400)',
          500: '#6366f1', 
          600: 'var(--primary-600)',
          700: 'var(--primary-700)',
          800: 'var(--primary-800)',
          900: 'var(--primary-900)',
        },

        'surface-0': 'var(--surface-0)',
        'surface-50': 'var(--surface-50)',
        'surface-100': 'var(--surface-100)',
      },

      borderRadius: {
        'prime-sm': 'var(--border-radius-sm)', 
        'prime': 'var(--border-radius)',      
        'prime-lg': 'var(--border-radius-lg)', 
      }
    },
  },
  plugins: [],
  
}

export default config