<?php
$pageTitle = 'الرئيسية';
require_once 'includes/header.php';

$stats = [
    'writers' => 500,
    'designers' => 300,
    'contents' => 1000
];

// Get latest books and designs
$latestBooks = getBooks(1, 4);
$latestDesigns = getDesigns(1, 4);
$topCreators = getTopCreators(4);
?>

<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #EC4899 100%); color: white; padding: 100px 0; position: relative; overflow: hidden;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-end">
                <h1 class="display-3 fw-bold mb-4">
                    منصتك لنشر <span style="color: #FBBF24;">الإبداع</span>
                </h1>
                <p class="lead mb-4 opacity-90">
                    أول منصة عربية تجمع الكتّاب والمصممين. انشر أعمالك، تواصل مع المبدعين، وابنِ جمهورك.
                </p>
                <div class="d-flex gap-3 justify-content-end flex-wrap">
                    <?php if (!isLoggedIn()): ?>
                        <a href="/auth/register.php" class="btn btn-warning btn-lg fw-bold rounded-pill">
                            ابدأ مجاناً →
                        </a>
                        <a href="#creations" class="btn btn-outline-light btn-lg fw-bold rounded-pill">
                            استكشف الإبداعات
                        </a>
                    <?php else: ?>
                        <a href="/my-creations.php" class="btn btn-warning btn-lg fw-bold rounded-pill">
                            إبداعاتي
                        </a>
                        <a href="#creations" class="btn btn-outline-light btn-lg fw-bold rounded-pill">
                            اكتشف المزيد
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Stats -->
                <div class="row mt-5 pt-3">
                    <div class="col-4 text-center">
                        <h3 class="display-6 fw-bold">+<?php echo $stats['writers']; ?></h3>
                        <p class="opacity-80">كاتب ومؤلف</p>
                    </div>
                    <div class="col-4 text-center">
                        <h3 class="display-6 fw-bold">+<?php echo $stats['designers']; ?></h3>
                        <p class="opacity-80">مصمم مبدع</p>
                    </div>
                    <div class="col-4 text-center">
                        <h3 class="display-6 fw-bold">+<?php echo $stats['contents']; ?></h3>
                        <p class="opacity-80">كتاب وتصميم</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="position-relative">
                    <div style="background: rgba(255,255,255,0.1); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px);">
                        <div class="row g-3">
                            <div class="col-6">
                                <div style="background: linear-gradient(135deg, #8B5CF6, #EC4899); border-radius: 15px; padding: 60px 20px; text-align: center; color: white;">
                                    <i class="fas fa-book fa-3x mb-3"></i>
                                    <p>الكتب</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="background: linear-gradient(135deg, #6366F1, #8B5CF6); border-radius: 15px; padding: 60px 20px; text-align: center; color: white; margin-top: 30px;">
                                    <i class="fas fa-palette fa-3x mb-3"></i>
                                    <p>التصاميم</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Creations Section -->
<section id="creations" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">أحدث الإبداعات</h2>
            <p class="lead text-muted">اكتشف أحدث الكتب والتصاميم من مجتمعنا المبدع</p>
        </div>
        
        <!-- Books -->
        <div class="mb-5">
            <h3 class="mb-4 fw-bold">
                <i class="fas fa-book text-primary"></i> أحدث الكتب
            </h3>
            <div class="row g-4">
                <?php if (!empty($latestBooks)): ?>
                    <?php foreach ($latestBooks as $book): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-img-top" style="background: linear-gradient(135deg, #6366F1, #8B5CF6); height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-book fa-4x opacity-50"></i>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                                    <p class="card-text text-muted small">
                                        بقلم: <strong><?php echo htmlspecialchars($book['full_name']); ?></strong>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($book['category'] ?? 'عام'); ?></span>
                                        <span class="text-muted small">
                                            <i class="fas fa-eye"></i> <?php echo $book['views']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top">
                                    <a href="/book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-outline-primary w-100">
                                        عرض الكتاب
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> لا توجد كتب منشورة حالياً
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Designs -->
        <div class="mb-5">
            <h3 class="mb-4 fw-bold">
                <i class="fas fa-palette text-danger"></i> أحدث التصاميم
            </h3>
            <div class="row g-4">
                <?php if (!empty($latestDesigns)): ?>
                    <?php foreach ($latestDesigns as $design): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-img-top" style="background: linear-gradient(135deg, #EC4899, #F59E0B); height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-palette fa-4x opacity-50"></i>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($design['title']); ?></h5>
                                    <p class="card-text text-muted small">
                                        بقلم: <strong><?php echo htmlspecialchars($design['full_name']); ?></strong>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-danger"><?php echo htmlspecialchars($design['category'] ?? 'عام'); ?></span>
                                        <span class="text-muted small">
                                            <i class="fas fa-eye"></i> <?php echo $design['views']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top">
                                    <a href="/design.php?id=<?php echo $design['id']; ?>" class="btn btn-sm btn-outline-danger w-100">
                                        عرض التصميم
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> لا توجد تصاميم منشورة حالياً
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="text-center">
            <a href="/books.php" class="btn btn-primary btn-lg me-2 mb-2">تصفح جميع الكتب</a>
            <a href="/designs.php" class="btn btn-danger btn-lg mb-2">تصفح جميع التصاميم</a>
        </div>
    </div>
</section>

<!-- Top Creators Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">أفضل المبدعين</h2>
            <p class="lead text-muted">تعرف على أبرز الكتاب والمصممين في مجتمعنا</p>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($topCreators)): ?>
                <?php foreach ($topCreators as $creator): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <div class="mb-3">
                                    <div style="width: 100px; height: 100px; margin: 0 auto; background: linear-gradient(135deg, #6366F1, #8B5CF6); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: bold;">
                                        <?php echo substr($creator['full_name'], 0, 1); ?>
                                    </div>
                                </div>
                                <h5 class="card-title"><?php echo htmlspecialchars($creator['full_name']); ?></h5>
                                <p class="text-primary fw-bold mb-2"><?php echo htmlspecialchars($creator['user_type']); ?></p>
                                <p class="text-muted small mb-3">
                                    <?php echo ($creator['books_count'] + $creator['designs_count']); ?> إبداع
                                </p>
                                <a href="/profile.php?id=<?php echo $creator['id']; ?>" class="btn btn-sm btn-outline-primary w-100">
                                    عرض الملف الشخصي
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> لا توجد مبدعين حالياً
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section style="background: linear-gradient(to left, #6366F1, #7C3AED); color: white; padding: 80px 0;">
    <div class="container text-center">
        <h2 class="display-4 fw-bold mb-4">هل أنت مبدع؟</h2>
        <p class="lead mb-4 opacity-90">
            انضم إلى آلاف المبدعين والكتاب العرب. شارك أعمالك وبناء جمهورك اليوم.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <?php if (!isLoggedIn()): ?>
                <a href="/auth/register.php" class="btn btn-light btn-lg fw-bold rounded-pill">
                    انضم الآن
                </a>
                <a href="/auth/login.php" class="btn btn-outline-light btn-lg fw-bold rounded-pill">
                    تسجيل الدخول
                </a>
            <?php else: ?>
                <a href="/upload-book.php" class="btn btn-light btn-lg fw-bold rounded-pill">
                    رفع كتاب
                </a>
                <a href="/upload-design.php" class="btn btn-outline-light btn-lg fw-bold rounded-pill">
                    رفع تصميم
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
