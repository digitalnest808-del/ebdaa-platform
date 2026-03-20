# دليل التثبيت - منصة إبداع

## المتطلبات الأساسية

قبل البدء، تأكد من توفر:

- **PHP 7.4 أو أحدث**
- **MySQL 5.7 أو أحدث**
- **Apache مع mod_rewrite مفعل**
- **Composer** (اختياري)

## خطوات التثبيت

### الخطوة 1: تحضير المشروع

```bash
# انسخ المشروع إلى مجلد الويب
cp -r ebdaa-platform /var/www/html/

# أو استخدم git
git clone https://github.com/yourusername/ebdaa-platform.git /var/www/html/ebdaa-platform
cd /var/www/html/ebdaa-platform
```

### الخطوة 2: تعيين الصلاحيات

```bash
# منح صلاحيات الكتابة للمجلدات
chmod -R 755 uploads/
chmod -R 755 config/
chmod 644 .htaccess

# إذا كنت تستخدم Apache مع suexec
chown -R www-data:www-data /var/www/html/ebdaa-platform
```

### الخطوة 3: إنشاء قاعدة البيانات

#### الطريقة 1: استخدام phpMyAdmin

1. افتح phpMyAdmin
2. انقر على "جديد" أو "New"
3. أنشئ قاعدة بيانات جديدة باسم `ebdaa_platform`
4. اختر الترميز `utf8mb4_unicode_ci`
5. انسخ محتوى `config/init_database.php` وشغله

#### الطريقة 2: استخدام سطر الأوامر

```bash
# تسجيل الدخول إلى MySQL
mysql -u root -p

# تشغيل الأوامر
CREATE DATABASE IF NOT EXISTS `ebdaa_platform` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ebdaa_platform`;

# ثم انسخ محتوى الجداول من config/init_database.php
```

#### الطريقة 3: استخدام PHP Script

```bash
# افتح المتصفح على:
http://localhost/ebdaa-platform/config/init_database.php

# أو شغل من سطر الأوامر:
php config/init_database.php
```

### الخطوة 4: تحديث إعدادات قاعدة البيانات

عدّل ملف `config/database.php`:

```php
private $host = 'localhost';      // عنوان الخادم
private $dbname = 'ebdaa_platform'; // اسم قاعدة البيانات
private $username = 'root';        // اسم المستخدم
private $password = '';            // كلمة المرور
```

### الخطوة 5: إضافة البيانات التجريبية (اختياري)

```bash
# عبر المتصفح:
http://localhost/ebdaa-platform/config/sample_data.php

# أو من سطر الأوامر:
php config/sample_data.php
```

### الخطوة 6: تفعيل mod_rewrite

تأكد من تفعيل `mod_rewrite` في Apache:

```bash
# على Ubuntu/Debian
sudo a2enmod rewrite

# أعد تشغيل Apache
sudo systemctl restart apache2
```

## التحقق من التثبيت

بعد إكمال الخطوات أعلاه:

1. افتح المتصفح على: `http://localhost/ebdaa-platform`
2. يجب أن ترى الصفحة الرئيسية
3. جرب تسجيل الدخول ببيانات التجربة:
   - البريد: `ahmed@example.com`
   - كلمة المرور: `password123`

## حل المشاكل الشائعة

### مشكلة: "فشل الاتصال بقاعدة البيانات"

**الحل:**
- تحقق من بيانات الاتصال في `config/database.php`
- تأكد من تشغيل خادم MySQL
- تحقق من أن قاعدة البيانات موجودة

### مشكلة: "الصفحة غير موجودة" (404)

**الحل:**
- تأكد من تفعيل `mod_rewrite`
- تحقق من ملف `.htaccess`
- تأكد من أن جميع الملفات موجودة

### مشكلة: "خطأ في الصلاحيات"

**الحل:**
```bash
chmod -R 755 uploads/
chmod -R 755 config/
chown -R www-data:www-data /var/www/html/ebdaa-platform
```

### مشكلة: "الملفات المرفوعة لا تعمل"

**الحل:**
- تأكد من وجود مجلد `uploads`
- تحقق من صلاحيات الكتابة
- تأكد من أن PHP يمكنه الكتابة في المجلد

## الإعدادات الموصى بها

### php.ini

```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
```

### Apache Configuration

```apache
<Directory /var/www/html/ebdaa-platform>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

## الأمان

### نصائح الأمان المهمة:

1. **غير كلمات المرور الافتراضية**
   ```bash
   php config/sample_data.php
   # ثم غير كلمات المرور من لوحة التحكم
   ```

2. **فعّل HTTPS**
   ```apache
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

3. **حماية ملفات الإعدادات**
   ```bash
   chmod 600 config/database.php
   ```

4. **تحديث PHP والمكتبات**
   ```bash
   sudo apt update && sudo apt upgrade
   ```

## الدعم والمساعدة

إذا واجهت مشاكل:

1. تحقق من ملف `README.md`
2. راجع السجلات في `/var/log/apache2/error.log`
3. تواصل مع الدعم: support@ebdaa.com

---

**تم التثبيت بنجاح! 🎉**

الآن يمكنك البدء في استخدام منصة إبداع.
