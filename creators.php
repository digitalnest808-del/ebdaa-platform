<?php
$pageTitle = 'المبدعين';
require_once 'includes/header.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$type = isset($_GET['type']) ? sanitize($_GET['type']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$limit = 12;
$offset = ($page - 1) * $limit;

try {
    // Build query
    $query = "SELECT u.*, COUNT(DISTINCT b.id) as books_count, COUNT(DISTINCT d.id) as designs_count 
              FROM users u 
              LEFT JOIN books b ON u.id = b.user_id AND b.is_published = TRUE
              LEFT JOIN designs d ON u.id = d.user_id AND d.is_published = TRUE
              WHERE u.is_active = TRUE";
    $params = [];
    
    if ($type && in_array($type, ['كاتب', 'مصمم', 'قارئ'])) {
        $query .= " AND u.user_type = ?";
        $params[] = $type;
    }
    
    if ($search) {
        $query .= " AND u.full_name LIKE ?";
        $params[] = "%$search%";
    }
    
    // Get total count
    $countStmt = db()->prepare(str_replace("SELECT u.*, COUNT(DISTINCT b.id) as books_count, COUNT(DISTINCT d.id) as designs_count", "SELECT COUNT(DISTINCT u.id) as count", $query));
    $countStmt->execute($params);
    $totalCreators = $countStmt->fetch()['count'];
    $totalPages = ceil($totalCreators / $limit);
    
    // Get creators
    $query .= " GROUP BY u.id ORDER BY (COUNT(DISTINCT b.id) + COUNT(DISTINCT d.id)) DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = db()->prepare($query);
    $stmt->execute($params);
    $creators = $stmt->fetchAll();
    
} catch (Exception $e) {
    $creators = [];
    $totalCreators = 0;
    $totalPages = 1;
}
?>

<section class="py-5 bg-light">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">المبدعين</h1>
        
        <!-- Search and Filter -->
        <div class="row mb-5">
            <div class="col-md-8">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="ابحث عن مبدع..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <form method="GET" class="d-flex gap-2">
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع الأنواع</option>
                        <option value="كاتب" <?php echo ($type === 'كاتب') ? 'selected' : ''; ?>>كاتب</option>
                        <option value="مصمم" <?php echo ($type === 'مصمم') ? 'selected' : ''; ?>>مصمم</option>
                        <option value="قارئ" <?php echo ($type === 'قارئ') ? 'selected' : ''; ?>>قارئ</option>
                    </select>
                </form>
            </div>
        </div>
        
        <!-- Creators Grid -->
        <div class="row g-4 mb-5">
            <?php if (!empty($creators)): ?>
                <?php foreach ($creators as $creator): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm text-center">
                            <div style="background: linear-gradient(135deg, #6366F1, #8B5CF6); height: 100px;"></div>
                            <div class="card-body position-relative" style="margin-top: -50px;">
                                <div style="width: 100px; height: 100px; margin: 0 auto 15px; background: linear-gradient(135deg, #6366F1, #8B5CF6); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; font-weight: bold; border: 4px solid white;">
                                    <?php echo substr($creator['full_name'], 0, 1); ?>
                                </div>
                                <h5 class="card-title"><?php echo htmlspecialchars($creator['full_name']); ?></h5>
                                <p class="text-primary fw-bold mb-2"><?php echo htmlspecialchars($creator['user_type']); ?></p>
                                <p class="text-muted small mb-3">
                                    <?php echo ($creator['books_count'] + $creator['designs_count']); ?> إبداع
                                </p>
                                <?php if ($creator['bio']): ?>
                                    <p class="small text-muted mb-3">
                                        <?php echo htmlspecialchars(substr($creator['bio'], 0, 100)); ?>...
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <a href="/profile.php?id=<?php echo $creator['id']; ?>" class="btn btn-sm btn-outline-primary w-100">
                                    عرض الملف الشخصي
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <p>لا توجد مبدعين مطابقين لبحثك</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&type=<?php echo urlencode($type); ?>&search=<?php echo urlencode($search); ?>">
                                السابق
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&type=<?php echo urlencode($type); ?>&search=<?php echo urlencode($search); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&type=<?php echo urlencode($type); ?>&search=<?php echo urlencode($search); ?>">
                                التالي
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
