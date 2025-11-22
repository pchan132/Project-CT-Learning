import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    // เพิ่มส่วนนี้ครับ
    server: {
        // 1. ให้ Server ฟังทุก IP (สำคัญมากสำหรับ Live Share)
        host: '0.0.0.0', 
        // 2. ตั้งค่า HMR ให้ชี้กลับมาที่ localhost ของเครื่องใครเครื่องมัน
        hmr: {
            host: 'localhost',
        },
    },
});
