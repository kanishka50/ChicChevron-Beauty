/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // Luxury Plum Theme
        'plum': {
          50: '#FAF7FB',
          100: '#F5EFF7',
          200: '#EDE4F0',
          300: '#E8D8EE',
          400: '#D4BDD9',
          500: '#9E7BA8',
          600: '#7A5C82',
          700: '#5C3D62',
          800: '#4A2D4F',
          900: '#3A2340',
          950: '#2A1830',
        },
        'gold': {
          50: '#FDFBF5',
          100: '#FAF5E6',
          200: '#F5EBC7',
          300: '#E8D8A8',
          400: '#D4BC7A',
          500: '#C9A962',
          600: '#B8944A',
          700: '#9A7A3D',
          800: '#7D6332',
          900: '#5F4B26',
          950: '#3D311A',
        },
        'lilac': {
          50: '#FDFCFE',
          100: '#FAF7FB',
          200: '#F5EFF7',
          300: '#EDE4F0',
          400: '#E8D8EE',
          500: '#D8C8E0',
          600: '#C4B0D0',
          700: '#A890B8',
          800: '#8A70A0',
          900: '#6C5080',
        },
        // Keep primary for backwards compatibility
        'primary': {
          50: '#FAF7FB',
          100: '#F5EFF7',
          200: '#EDE4F0',
          300: '#E8D8EE',
          400: '#D4BDD9',
          500: '#9E7BA8',
          600: '#7A5C82',
          700: '#5C3D62',
          800: '#4A2D4F',
          900: '#3A2340',
          950: '#2A1830',
        },
      },
      fontFamily: {
        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
        'display': ['Playfair Display', 'serif'],
        'body': ['Inter', 'sans-serif'],
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'fade-in-up': 'fadeInUp 0.6s ease-out',
        'fade-in-down': 'fadeInDown 0.6s ease-out',
        'slide-in-left': 'slideInLeft 0.5s ease-out',
        'slide-in-right': 'slideInRight 0.5s ease-out',
        'bounce-subtle': 'bounceSubtle 2s infinite',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'shimmer': 'shimmer 2s linear infinite',
        'float': 'float 3s ease-in-out infinite',
        'marquee': 'marquee 25s linear infinite',
        'marquee-reverse': 'marquee-reverse 25s linear infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        fadeInDown: {
          '0%': { opacity: '0', transform: 'translateY(-30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideInLeft: {
          '0%': { opacity: '0', transform: 'translateX(-30px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
        slideInRight: {
          '0%': { opacity: '0', transform: 'translateX(30px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
        bounceSubtle: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        shimmer: {
          '0%': { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-20px)' },
        },
        marquee: {
          '0%': { transform: 'translateX(0%)' },
          '100%': { transform: 'translateX(-100%)' },
        },
        'marquee-reverse': {
          '0%': { transform: 'translateX(-100%)' },
          '100%': { transform: 'translateX(0%)' },
        },
      },
      backgroundImage: {
        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
        'gradient-plum': 'linear-gradient(135deg, #4A2D4F 0%, #5C3D62 50%, #7A5C82 100%)',
        'gradient-lilac': 'linear-gradient(135deg, #EDE4F0 0%, #E8D8EE 50%, #F5EFF7 100%)',
        'gradient-gold': 'linear-gradient(135deg, #C9A962 0%, #D4BC7A 50%, #E8D8A8 100%)',
        'shimmer-gold': 'linear-gradient(90deg, transparent 0%, rgba(201, 169, 98, 0.3) 50%, transparent 100%)',
      },
      boxShadow: {
        'elegant': '0 4px 20px -2px rgba(74, 45, 79, 0.1)',
        'elegant-lg': '0 10px 40px -5px rgba(74, 45, 79, 0.15)',
        'gold': '0 4px 20px -2px rgba(201, 169, 98, 0.3)',
        'card-hover': '0 20px 40px -10px rgba(74, 45, 79, 0.2)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}