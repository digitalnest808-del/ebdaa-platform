# دليل نشر منصة إبداع على Heroku

## المتطلبات

- حساب GitHub (مجاني)
- حساب Heroku (مجاني)
- Git مثبت على جهازك

## خطوات النشر

### الخطوة 1: إنشاء حساب Heroku

1. اذهب إلى [heroku.com](https://www.heroku.com)
2. انقر على "Sign Up"
3. أملأ البيانات وأنشئ حساب
4. تحقق من بريدك الإلكتروني

### الخطوة 2: تثبيت Heroku CLI

#### على Windows:
```bash
# حمّل المثبت من:
https://devcenter.heroku.com/articles/heroku-cli

# أو استخدم Chocolatey:
choco install heroku-cli
```

#### على macOS:
```bash
brew tap heroku/brew && brew install heroku
```

#### على Linux:
```bash
curl https://cli-assets.heroku.com/install.sh | sh
```

### الخطوة 3: تسجيل الدخول إلى Heroku

```bash
heroku login
```

سيفتح متصفح لتسجيل الدخول. أكمل عملية التسجيل.

### الخطوة 4: إنشاء تطبيق Heroku

```bash
cd /path/to/ebdaa-php

# أنشئ تطبيق جديد
heroku create ebdaa-platform

# أو اختر اسماً مختلفاً:
heroku create your-app-name
```

### الخطوة 5: إضافة قاعدة بيانات MySQL

```bash
# أضف JawsDB MySQL (مجاني)
heroku addons:create jawsdb:kitefin

# تحقق من الإضافة
heroku addons
```

### الخطوة 6: تحديث إعدادات قاعدة البيانات

Heroku سيضيف متغير بيئة `JAWSDB_URL`. نحتاج تحديث `config/database.php`:

```php
<?php
// الحصول على بيانات الاتصال من Heroku
if (getenv('JAWSDB_URL')) {
    $url = parse_url(getenv('JAWSDB_URL'));
    $host = $url['host'];
    $dbname = ltrim($url['path'], '/');
    $username = $url['user'];
    $password = $url['pass'];
} else {
    // الإعدادات المحلية
    $host = 'localhost';
    $dbname = 'ebdaa_platform';
    $username = 'root';
    $password = '';
}

class Database {
    private static $instance = null;
    private $connection;
    
    private $host;
    private $dbname;
    private $username;
    private $password;
    
    private function __construct() {
        global $host, $dbname, $username, $password;
        
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

function db() {
    return Database::getInstance()->getConnection();
}
?>
```

### الخطوة 7: إنشاء ملف git وتحميل المشروع

```bash
# تهيئة git
git init

# أضف جميع الملفات
git add .

# أنشئ commit أول
git commit -m "Initial commit: Ebdaa Platform"

# ادفع إلى Heroku
git push heroku master
```

### الخطوة 8: إنشاء جداول قاعدة البيانات

```bash
# شغّل الأوامر على Heroku
heroku run php config/init_database.php
```

### الخطوة 9: إضافة البيانات التجريبية

```bash
heroku run php config/sample_data.php
```

### الخطوة 10: فتح التطبيق

```bash
heroku open
```

أو افتح المتصفح على الرابط الذي ستراه في الطرفية.

---

## الرابط النهائي

بعد النشر، سيكون رابط تطبيقك:

```
https://your-app-name.herokuapp.com
```

مثال:
```
https://ebdaa-platform.herokuapp.com
```

---

## بيانات الدخول التجريبية

```
البريد الإلكتروني: ahmed@example.com
كلمة المرور: password123
```

---

## الأوامر المفيدة

```bash
# عرض السجلات
heroku logs --tail

# عرض متغيرات البيئة
heroku config

# تعيين متغير بيئة
heroku config:set KEY=value

# إعادة تشغيل التطبيق
heroku restart

# فتح وحدة تحكم Heroku
heroku console

# حذف التطبيق
heroku apps:destroy --app your-app-name
```

---

## حل المشاكل

### مشكلة: "Application error"

```bash
# تحقق من السجلات
heroku logs --tail

# أعد تشغيل التطبيق
heroku restart
```

### مشكلة: "Database connection error"

```bash
# تحقق من متغيرات البيئة
heroku config

# تأكد من أن JawsDB مثبت
heroku addons
```

### مشكلة: "Permission denied"

```bash
# تحقق من صلاحيات الملفات
heroku run chmod -R 755 uploads/
```

---

## الخطوات التالية

بعد النشر الناجح:

1. **اختبر الموقع** - تأكد من أن جميع الميزات تعمل
2. **أضف نطاق خاص** - يمكنك شراء نطاق وربطه بـ Heroku
3. **فعّل HTTPS** - Heroku يفعّله تلقائياً
4. **راقب الأداء** - استخدم لوحة تحكم Heroku

---

## الدعم

للمزيد من المعلومات:
- [توثيق Heroku](https://devcenter.heroku.com)
- [JawsDB Documentation](https://www.jawsdb.com/docs)

---

**تم النشر بنجاح! 🎉**
