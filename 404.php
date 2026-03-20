<?php
$pageTitle = 'الصفحة غير موجودة';
require_once 'includes/header.php';
?>

<section class="py-5" style="min-height: 60vh; display: flex; align-items: center;">
    <div class="container text-center">
        <div style="font-size: 5rem; color: #6366F1; margin-bottom: 20px;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h1 class="display-1 fw-bold mb-3">404</h1>
        <h2 class="mb-4">الصفحة غير موجودة</h2>
        <p class="lead text-muted mb-4">
            عذراً، الصفحة التي تبحث عنها غير موجودة أو تم حذفها.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="/" class="btn btn-primary btn-lg">
                <i class="fas fa-home"></i> العودة للرئيسية
            </a>
            <a href="/books.php" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-book"></i> تصفح الكتب
            </a>
            <a href="/designs.php" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-palette"></i> تصفح التصاميم
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
