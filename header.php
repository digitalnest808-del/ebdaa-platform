<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - إبداع' : 'إبداع - منصة النشر والإبداع العربي'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Google Fonts - Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/assets/css/style.css">
    
    <style>
        :root {
            --primary-color: #6366F1;
            --secondary-color: #8B5CF6;
            --accent-color: #EC4899;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
            --dark-color: #111827;
            --light-color: #F9FAFB;
        }
        
        * {
            font-family: 'Tajawal', 'Almarai', sans-serif;
        }
        
        body {
            background-color: var(--light-color);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 900;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            transform: translateY(-4px);
        }
        
        .alert-success {
            background-color: #D1FAE5;
            color: #065F46;
            border-color: #6EE7B7;
        }
        
        .alert-danger {
            background-color: #FEE2E2;
            color: #7F1D1D;
            border-color: #FCA5A5;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-book"></i> إ<span style="color: #FBBF24;">بداع</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/books.php">الكتب</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/designs.php">التصاميم</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/creators.php">المبدعين</a>
                    </li>
                    
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="/uploads/profiles/<?php echo $currentUser['profile_image']; ?>" 
                                     alt="<?php echo $currentUser['full_name']; ?>" 
                                     class="rounded-circle" width="30" height="30">
                                <?php echo $currentUser['full_name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/profile.php?id=<?php echo $currentUser['id']; ?>">ملفي الشخصي</a></li>
                                <li><a class="dropdown-item" href="/my-creations.php">إبداعاتي</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php if (isAdmin()): ?>
                                    <li><a class="dropdown-item" href="/admin/">لوحة التحكم</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="/auth/logout.php">تسجيل الخروج</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/auth/login.php">تسجيل الدخول</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-warning text-dark ms-2" href="/auth/register.php">إنشاء حساب</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <div class="container mt-3">
        <?php displayFlash(); ?>
    </div>
</body>
</html>
