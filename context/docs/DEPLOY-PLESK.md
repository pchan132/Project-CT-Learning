# 🚀 Deploy CT-Learning บน Plesk ด้วย Git

## สารบัญ
1. [เตรียมความพร้อม](#1-เตรียมความพร้อม)
2. [ตั้งค่า Plesk](#2-ตั้งค่า-plesk)
3. [เชื่อมต่อ Git Repository](#3-เชื่อมต่อ-git-repository)
4. [ตั้งค่า Environment](#4-ตั้งค่า-environment)
5. [ติดตั้ง Dependencies](#5-ติดตั้ง-dependencies)
6. [ตั้งค่า Database](#6-ตั้งค่า-database)
7. [ตั้งค่า Document Root](#7-ตั้งค่า-document-root)
8. [ตั้งค่า SSL](#8-ตั้งค่า-ssl)
9. [Post-Deployment Script](#9-post-deployment-script)
10. [Troubleshooting](#10-troubleshooting)

---

## 1. เตรียมความพร้อม

### ความต้องการของระบบ
- PHP >= 8.1 พร้อม Extensions:
  - BCMath, Ctype, cURL, DOM, Fileinfo
  - JSON, Mbstring, OpenSSL, PDO, PDO_MySQL
  - Tokenizer, XML, GD
- MySQL >= 8.0 หรือ MariaDB >= 10.3
- Composer
- Node.js >= 18.x และ npm
- Git

### ตรวจสอบ Local Project
```bash
# ตรวจสอบว่า commit ล่าสุดแล้ว
git status

# Push ไปยัง GitHub
git push origin main
```

---

## 2. ตั้งค่า Plesk

### 2.1 สร้าง Domain/Subdomain

1. Login เข้า Plesk Panel
2. ไปที่ **Websites & Domains**
3. คลิก **Add Domain** หรือ **Add Subdomain**
4. กรอกชื่อ Domain: `ct-learning.yourdomain.com`
5. เลือก **Hosting type**: Website hosting
6. คลิก **OK**

### 2.2 ตั้งค่า PHP Version

1. ไปที่ Domain ที่สร้าง
2. คลิก **PHP Settings**
3. เลือก **PHP version**: 8.1 หรือสูงกว่า
4. ตั้งค่า PHP Options:
   ```
   memory_limit = 512M
   max_execution_time = 300
   post_max_size = 100M
   upload_max_filesize = 100M
   ```
5. เปิด Extensions ที่จำเป็น:
   - ✅ gd
   - ✅ mbstring
   - ✅ pdo_mysql
   - ✅ zip
   - ✅ curl
   - ✅ fileinfo
6. คลิก **Apply**

---

## 3. เชื่อมต่อ Git Repository

### 3.1 สร้าง SSH Key บน Plesk

1. ไปที่ Domain → **Git**
2. หากยังไม่มี SSH Key ให้สร้างใหม่:
   - คลิก **Generate Key Pair**
   - Copy **Public Key**

### 3.2 เพิ่ม Deploy Key บน GitHub

1. ไปที่ GitHub Repository → **Settings** → **Deploy keys**
2. คลิก **Add deploy key**
3. ตั้งชื่อ: `Plesk Server`
4. วาง Public Key ที่ copy มา
5. ✅ Allow write access (ถ้าต้องการ)
6. คลิก **Add key**

### 3.3 เชื่อมต่อ Repository

1. กลับไป Plesk → Domain → **Git**
2. คลิก **Clone Repository**
3. กรอกข้อมูล:
   - **Repository URL**: `git@github.com:pchan132/Project-CT-Learning.git`
   - **Branch**: `main`
   - **Directory**: ปล่อยว่าง (จะ clone ลงใน root)
4. คลิก **OK**

---

## 4. ตั้งค่า Environment

### 4.1 สร้างไฟล์ .env

1. ไปที่ Plesk → **File Manager**
2. เข้าไปใน folder ของ domain
3. Copy `.env.example` เป็น `.env`
4. แก้ไข `.env`:

```env
APP_NAME="CT Learning"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://ct-learning.yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ct_learning_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 5. ติดตั้ง Dependencies

### 5.1 เข้า SSH Terminal

1. ไปที่ Plesk → Domain → **SSH Terminal** หรือใช้ SSH client
2. เข้าไปใน directory ของ project:

```bash
cd /var/www/vhosts/yourdomain.com/ct-learning.yourdomain.com
```

### 5.2 ติดตั้ง Composer Dependencies

```bash
# ติดตั้ง dependencies สำหรับ production
composer install --optimize-autoloader --no-dev

# Generate Application Key
php artisan key:generate
```

### 5.3 ติดตั้ง Node.js Dependencies และ Build

```bash
# ติดตั้ง node modules
npm install

# Build assets สำหรับ production
npm run build
```

---

## 6. ตั้งค่า Database

### 6.1 สร้าง Database บน Plesk

1. ไปที่ **Databases**
2. คลิก **Add Database**
3. กรอกข้อมูล:
   - **Database name**: `ct_learning_db`
   - **Database user**: `ct_learning_user`
   - **Password**: สร้าง password ที่ปลอดภัย
4. คลิก **OK**

### 6.2 Run Migrations

```bash
# Run migrations
php artisan migrate --force

# (Optional) Seed ข้อมูลเริ่มต้น
php artisan db:seed --force
```

---

## 7. ตั้งค่า Document Root

### 7.1 เปลี่ยน Document Root ไปที่ public

1. ไปที่ Plesk → Domain → **Hosting Settings**
2. เปลี่ยน **Document root**: 
   - จาก: `httpdocs` 
   - เป็น: `httpdocs/public`
3. คลิก **OK**

### 7.2 ตั้งค่า Permissions

```bash
# ตั้งค่า permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# สร้าง storage link
php artisan storage:link
```

---

## 8. ตั้งค่า SSL

### 8.1 ติดตั้ง SSL Certificate

1. ไปที่ Domain → **SSL/TLS Certificates**
2. คลิก **Install** ใต้ Let's Encrypt
3. กรอก Email
4. ✅ Include www subdomain (ถ้าต้องการ)
5. ✅ Redirect from http to https
6. คลิก **Install**

---

## 9. Post-Deployment Script

### 9.1 ตั้งค่า Auto-Deploy

1. ไปที่ Plesk → Domain → **Git**
2. คลิกที่ Repository → **Repository Settings**
3. ✅ Enable **Webhook** (Copy URL ไว้)
4. ใส่ **Post-deployment script**:

```bash
#!/bin/bash

# เข้าไปใน project directory
cd $DOCUMENT_ROOT/..

# Update composer dependencies
composer install --optimize-autoloader --no-dev

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Build assets (ถ้าจำเป็น)
# npm install
# npm run build

echo "Deployment completed!"
```

### 9.2 ตั้งค่า GitHub Webhook

1. ไปที่ GitHub Repository → **Settings** → **Webhooks**
2. คลิก **Add webhook**
3. กรอกข้อมูล:
   - **Payload URL**: URL ที่ copy จาก Plesk
   - **Content type**: `application/json`
   - **Secret**: ปล่อยว่างหรือใส่ตามที่ Plesk กำหนด
   - **Events**: Just the push event
4. คลิก **Add webhook**

---

## 10. Troubleshooting

### ปัญหา: 500 Internal Server Error

```bash
# ตรวจสอบ Laravel logs
tail -f storage/logs/laravel.log

# ตรวจสอบ permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### ปัญหา: Class not found

```bash
# Clear และ rebuild autoload
composer dump-autoload
php artisan clear-compiled
php artisan optimize
```

### ปัญหา: Assets ไม่โหลด

```bash
# ตรวจสอบ storage link
php artisan storage:link

# Build assets ใหม่
npm run build
```

### ปัญหา: Database connection error

1. ตรวจสอบ `.env` ว่าข้อมูล DB ถูกต้อง
2. ตรวจสอบว่า Database user มีสิทธิ์เข้าถึง

```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### ปัญหา: Images/Files ไม่แสดง

```bash
# สร้าง symbolic link
php artisan storage:link

# ตรวจสอบ permissions
chmod -R 755 public/storage
chmod -R 755 storage/app/public
```

### ปัญหา: Thai font ใน PDF ไม่แสดง

```bash
# Clear font cache
rm -rf storage/fonts/*

# ตรวจสอบ GD extension
php -m | grep gd
```

---

## 📋 Checklist หลัง Deploy

- [ ] เว็บไซต์เข้าถึงได้ผ่าน HTTPS
- [ ] Login/Register ทำงานได้
- [ ] Upload รูปภาพได้
- [ ] ดาวน์โหลด Certificate PDF ได้
- [ ] ส่ง Email ได้ (ถ้าใช้)
- [ ] Dark mode ทำงาน
- [ ] Mobile responsive ใช้งานได้

---

## 🔄 การ Update โค้ด

หลังจาก push code ใหม่ไป GitHub:

### วิธีที่ 1: Auto-deploy (ถ้าตั้ง Webhook แล้ว)
- Push ไป GitHub จะ auto deploy

### วิธีที่ 2: Manual deploy
1. ไปที่ Plesk → Domain → **Git**
2. คลิก **Pull Now**

### วิธีที่ 3: SSH
```bash
cd /var/www/vhosts/yourdomain.com/ct-learning.yourdomain.com
git pull origin main
composer install --no-dev
npm run build
php artisan migrate --force
php artisan optimize
```

---

## 📞 Support

หากมีปัญหาในการ deploy กรุณาตรวจสอบ:
1. Laravel Log: `storage/logs/laravel.log`
2. Apache/Nginx Error Log ใน Plesk
3. PHP Error Log ใน Plesk → Domain → Logs

---

*อัปเดตล่าสุด: December 2025*
