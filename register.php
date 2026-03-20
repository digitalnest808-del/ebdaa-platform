<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('../index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_type = sanitize($_POST['user_type'] ?? 'قارئ');
    
    $errors = [];
    
    // Validation
    if (empty($full_name)) {
        $errors[] = 'الاسم الكامل مطلوب';
    } elseif (strlen($full_name) < 3) {
        $errors[] = 'الاسم يجب أن يكون 3 أحرف على الأقل';
    }
    
    if (empty($email)) {
        $errors[] = 'البريد الإلكتروني مطلوب';
    } elseif (!isValidEmail($email)) {
        $errors[] = 'البريد الإلكتروني غير صالح';
    }
    
    if (empty($password)) {
        $errors[] = 'كلمة المرور مطلوبة';
    } elseif (strlen($password) < 6) {
        $errors[] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'كلمة المرور غير متطابقة';
    }
    
    if (!in_array($user_type, ['كاتب', 'مصمم', 'قارئ'])) {
        $errors[] = 'نوع الحساب غير صالح';
    }
    
    if (empty($errors)) {
        try {
            // Check if email already exists
            $stmt = db()->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if (!$stmt->fetch()) {
                $hashed_password = hashPassword($password);
                $stmt = db()->prepare("
                    INSERT INTO users (full_name, email, password, user_type) 
                    VALUES (?, ?, ?, ?)
                ");
                
                if ($stmt->execute([$full_name, $email, $hashed_password, $user_type])) {
                    $success = 'تم التسجيل بنجاح! يمكنك الآن تسجيل الدخول.';
                    // Clear form
                    $full_name = $email = $password = $confirm_password = '';
                } else {
                    $error = 'حدث خطأ أثناء التسجيل. حاول مرة أخرى.';
                }
            } else {
                $error = 'البريد الإلكتروني مستخدم بالفعل';
            }
        } catch (Exception $e) {
            $error = 'حدث خطأ في النظام. حاول مرة أخرى لاحقاً.';
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل جديد - إبداع</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', 'Almarai', sans-serif;
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #EC4899 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        h1 {
            text-align: center;
            color: #1F2937;
            margin-bottom: 10px;
            font-size: 2rem;
        }
        
        .subtitle {
            text-align: center;
            color: #6B7280;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #4B5563;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            font-size: 16px;
            font-family: 'Tajawal', 'Almarai', sans-serif;
            transition: all 0.3s;
        }
        
        input:focus, select:focus {
            border-color: #6366F1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to left, #6366F1, #8B5CF6);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Tajawal', 'Almarai', sans-serif;
        }
        
        button:hover {
            background: linear-gradient(to left, #8B5CF6, #EC4899);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }
        
        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6B7280;
            font-size: 0.95rem;
        }
        
        .footer a {
            color: #6366F1;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .footer a:hover {
            color: #8B5CF6;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 480px) {
            .auth-container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>✨ إنشاء حساب جديد</h1>
        <p class="subtitle">انضم إلى مجتمع المبدعين العرب</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <strong>خطأ:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <strong>نجاح:</strong> <?php echo $success; ?>
                <br><br>
                <a href="login.php" style="color: #065F46; text-decoration: underline;">انقر هنا للدخول</a>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="full_name">الاسم الكامل</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $full_name ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" value="<?php echo $email ?? ''; ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">تأكيد كلمة المرور</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="user_type">نوع الحساب</label>
                <select id="user_type" name="user_type">
                    <option value="قارئ" <?php echo (($user_type ?? '') === 'قارئ') ? 'selected' : ''; ?>>قارئ</option>
                    <option value="كاتب" <?php echo (($user_type ?? '') === 'كاتب') ? 'selected' : ''; ?>>كاتب</option>
                    <option value="مصمم" <?php echo (($user_type ?? '') === 'مصمم') ? 'selected' : ''; ?>>مصمم</option>
                </select>
            </div>
            
            <button type="submit">إنشاء الحساب</button>
        </form>
        
        <div class="footer">
            لديك حساب بالفعل؟ <a href="login.php">تسجيل الدخول</a>
        </div>
    </div>
</body>
</html>
