/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{js,jsx,ts,tsx}",
  ],
  theme: {
    extend: {
      keyframes:{
        'slide-down': {
          'from': { transform: 'translateY(-50px)', opacity: '0' },
          'to': { transform: 'translateY(0)', opacity: '1' },
        },
        'scale-up': { 
          'from': { transform: 'scale(0.9)', opacity: '0' },
          'to': { transform: 'scale(1)', opacity: '1' },
        },
        'fadeInScale': { 
          'from': { opacity: '0', transform: 'translateY(-20px) scale(0.95)' },
          'to': { opacity: '1', transform: 'translateY(0) scale(1)' },
        }
      },
      animation: {
        'slide-down': 'slide-down 0.3s ease-out',
        'scale-up': 'scale-up 0.3s ease-out', 
        'fadeInScale': 'fadeInScale 0.3s forwards ease-out',
      },
      colors: {
        // Definisikan warna kustom di sini
        'emerald-300': '#98fb98', 
        'emerald-800': '#2e8b57',
        'blue-600': '#3b82f6', 
        'blue-700': '#2563eb', 
        'seagreen': '#2E8B57',
        'green-600': '#28a745', 
        'red-600': '#dc3545',   
        'blue-600': '#007bff',  
        'yellow-400': '#ffc107', 
        'orange-500': '#fd7e14', 
        'gray-50': '#f8f8f8', 
        'gray-100': '#f4f7f6', 
        'gray-200': '#eee',   
        'gray-600': '#666',
        'gray-700': '#444',
        'gray-800': '#333',
        'emerald-600': '#2E8B57',
      },
      boxShadow: {
        'xl': '15px 15px 5px rgba(0, 0, 0, 0.1)', 
      }
    },
  },
  plugins: [ 
    require('tailwind-scrollbar'),
   ],
}