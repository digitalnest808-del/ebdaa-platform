<?php
$pageTitle = 'التصاميم';
require_once 'includes/header.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$limit = 12;
$offset = ($page - 1) * $limit;

try {
    // Build query
    $query = "SELECT d.*, u.full_name, u.profile_image FROM designs d JOIN users u ON d.user_id = u.id WHERE d.is_published = TRUE";
    $params = [];
    
    if ($category) {
        $query .= " AND d.category = ?";
        $params[] = $category;
    }
    
    if ($search) {
        $query .= " AND (d.title LIKE ? OR d.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    // Get total count
    $countStmt = db()->prepare(str_replace("SELECT d.*, u.full_name, u.profile_image", "SELECT COUNT(*) as count", $query));
    $countStmt->execute($params);
    $totalDesigns = $countStmt->fetch()['count'];
    $totalPages = ceil($totalDesigns / $limit);
    
    // Get designs
    $query .= " ORDER BY d.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = db()->prepare($query);
    $stmt->execute($params);
    $designs = $stmt->fetchAll();
    
    // Get categories
    $categoriesStmt = db()->query("SELECT DISTINCT category FROM designs WHERE is_published = TRUE ORDER BY category");
    $categories = $categoriesStmt->fetchAll();
    
} catch (Exception $e) {
    $designs = [];
    $categories = [];
    $totalDesigns = 0;
    $totalPages = 1;
}
?>

<section class="py-5 bg-light">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">التصاميم</h1>
        
        <!-- Search and Filter -->
        <div class="row mb-5">
            <div class="col-md-8">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="ابحث عن تصميم..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <form method="GET" class="d-flex gap-2">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع التصنيفات</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo ($category === $cat['category']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
        
        <!-- Designs Grid -->
        <div class="row g-4 mb-5">
            <?php if (!empty($designs)): ?>
                <?php foreach ($designs as $design): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-img-top" style="background: linear-gradient(135deg, #EC4899, #F59E0B); height: 200px; display: flex; align-items: center; justify-content: center; color: white; position: relative;">
                                <i class="fas fa-palette fa-4x opacity-50"></i>
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">جديد</span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($design['title']); ?></h5>
                                <p class="card-text text-muted small mb-3">
                                    بقلم: <strong><?php echo htmlspecialchars($design['full_name']); ?></strong>
                                </p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-danger"><?php echo htmlspecialchars($design['category'] ?? 'عام'); ?></span>
                                    <span class="text-muted small">
                                        <i class="fas fa-eye"></i> <?php echo $design['views']; ?>
                                    </span>
                                </div>
                                <p class="card-text small text-muted">
                                    <?php echo substr(htmlspecialchars($design['description'] ?? ''), 0, 80) . '...'; ?>
                                </p>
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
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <p>لا توجد تصاميم مطابقة لبحثك</p>
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
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&category=<?php echo urlencode($category); ?>&search=<?php echo urlencode($search); ?>">
                                السابق
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&category=<?php echo urlencode($category); ?>&search=<?php echo urlencode($search); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&category=<?php echo urlencode($category); ?>&search=<?php echo urlencode($search); ?>">
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
