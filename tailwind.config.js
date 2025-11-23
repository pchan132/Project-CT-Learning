import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class', // ใช้คลาส 'dark' ใน <html> เพื่อสลับโหมด

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary-blue': '#1e3a8a', // Navy Blue เข้มสำหรับ Header
                'background-light': '#f9fafb', // สีพื้นหลังขาวนวล
                'card-light': '#ffffff', // สี Card สว่าง
                'background-dark': '#111827', // สีพื้นหลังโหมดมืด
                'card-dark': '#1f2937', // สี Card โหมดมืด
                'accent-glow': '#3b82f6', // สีฟ้าสำหรับการเน้น
            },
            boxShadow: {
                '3xl': '0 35px 60px -15px rgba(0, 0, 0, 0.3)',
                'subtle-white': '0 4px 10px rgba(0, 0, 0, 0.05)',
                'subtle-dark': '0 4px 10px rgba(255, 255, 255, 0.05)',
            },
        },
    },

    plugins: [forms],
};
