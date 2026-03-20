<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('../index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'البريد الإلكتروني وكلمة المرور مطلوبان';
    } else {
        try {
            $stmt = db()->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && verifyPassword($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['is_admin'] = $user['is_admin'];
                
                // Update last login
                $stmt = db()->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
                
                redirect('../index.php');
            } else {
                $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            }
        } catch (Exception $e) {
            $error = 'حدث خطأ في النظام. حاول مرة أخرى لاحقاً.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - إبداع</title>
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
            max-width: 450px;
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
        
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            font-size: 16px;
            font-family: 'Tajawal', 'Almarai', sans-serif;
            transition: all 0.3s;
        }
        
        input:focus {
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
        
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .remember-me input {
            width: auto;
            margin-left: 8px;
        }
        
        @media (max-width: 480px) {
            .auth-container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>🔐 مرحباً بعودتك</h1>
        <p class="subtitle">تسجيل الدخول إلى حسابك</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <strong>خطأ:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin-bottom: 0;">تذكرني</label>
            </div>
            
            <button type="submit">دخول</button>
        </form>
        
        <div class="footer">
            ليس لديك حساب؟ <a href="register.php">أنشئ حساب جديد</a>
        </div>
    </div>
</body>
</html>
