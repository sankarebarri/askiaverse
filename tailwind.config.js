/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.{html,js,php}",
    "./public/**/*.{html,js,php}",
    "./src/**/*.{html,js,php}",
    "./resources/views/**/*.php"
  ],
  theme: {
    extend: {
      // Askiaverse Color Palette
      colors: {
        primary: {
          DEFAULT: '#2A4D69',    // Deep Blue
          hover: '#4B8691',      // Teal Blue
        },
        secondary: {
          DEFAULT: '#4B8691',    // Teal Blue
          hover: '#63B7AF',      // Mint Green
        },
        success: {
          DEFAULT: '#63B7AF',    // Mint Green
        },
        dark: {
          bg: '#1E2B3A',         // Dark Navy
          surface: '#253549',    // Lighter Navy
        },
        text: {
          DEFAULT: '#EAF0F1',    // Light Gray
          muted: 'rgba(234, 240, 241, 0.7)',
        },
        // Additional utility colors
        overlay: 'rgba(0, 0, 0, 0.2)',
        'overlay-light': 'rgba(255, 255, 255, 0.1)',
        'glass-bg': 'rgba(23, 31, 42, 0.7)',
      },
      
      // Typography
      fontFamily: {
        'headings': ['Poppins', 'sans-serif'],
        'body': ['Lato', 'sans-serif'],
      },
      
      // Spacing and Layout
      maxWidth: {
        'container': '1100px',
        'quiz': '800px',
        'dashboard': '1400px',
      },
      
      // Custom heights
      maxHeight: {
        '80vh': '80vh',
      },
      
      // Border Radius
      borderRadius: {
        'xl': '16px',
        '2xl': '20px',
      },
      
      // Shadows
      boxShadow: {
        'glass': '0 10px 30px rgba(0,0,0,0.2)',
        'hud': '0 4px 12px rgba(0,0,0,0.3)',
        'modal': '0 20px 40px rgba(0,0,0,0.4)',
      },
      
      // Animations
      animation: {
        'fade-in': 'fadeIn 0.3s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'pulse-glow': 'pulse-glow 2s ease-in-out infinite',
        'xp-fill': 'xpFill 0.5s ease-in-out',
      },
      
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        'pulse-glow': {
          '0%, 100%': { 
            boxShadow: '0 0 20px rgba(99, 183, 175, 0.5)',
          },
          '50%': { 
            boxShadow: '0 0 30px rgba(99, 183, 175, 0.8)',
          },
        },
        xpFill: {
          '0%': { width: '0%' },
          '100%': { width: 'var(--xp-percentage)' },
        },
      },
      
      // Backdrop Filter
      backdropBlur: {
        'xs': '2px',
        'sm': '4px',
        'md': '8px',
        'lg': '15px',
      },
      
      // Custom utilities
      backgroundImage: {
        'gradient-radial': 'radial-gradient(ellipse at bottom, #1B2735 0%, #090A0F 100%)',
        'gradient-primary': 'linear-gradient(90deg, #2A4D69, #4B8691)',
        'gradient-success': 'linear-gradient(90deg, #4B8691, #63B7AF)',
      },
    },
  },
  plugins: [],
}