<?php
/**
 * Utility Functions
 * Common functions used throughout the application
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current logged-in user
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    try {
        $stmt = db()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    if (!isLoggedIn()) {
        return false;
    }
    
    $user = getCurrentUser();
    return $user && $user['is_admin'] === 1;
}

/**
 * Hash password using bcrypt
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Generate random token
 */
function generateToken() {
    return bin2hex(random_bytes(32));
}

/**
 * Set flash message
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get flash message
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Display flash message
 */
function displayFlash() {
    $flash = getFlash();
    if ($flash) {
        $class = $flash['type'] === 'success' ? 'alert-success' : 'alert-danger';
        echo "<div class='alert $class alert-dismissible fade show' role='alert'>
                {$flash['message']}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    }
}

/**
 * Get user by email
 */
function getUserByEmail($email) {
    try {
        $stmt = db()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get user by ID
 */
function getUserById($id) {
    try {
        $stmt = db()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get all books with pagination
 */
function getBooks($page = 1, $limit = 12) {
    try {
        $offset = ($page - 1) * $limit;
        $stmt = db()->prepare("
            SELECT b.*, u.full_name, u.profile_image 
            FROM books b 
            JOIN users u ON b.user_id = u.id 
            WHERE b.is_published = TRUE 
            ORDER BY b.created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get book by ID
 */
function getBookById($id) {
    try {
        $stmt = db()->prepare("
            SELECT b.*, u.full_name, u.profile_image, u.id as user_id 
            FROM books b 
            JOIN users u ON b.user_id = u.id 
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get all designs with pagination
 */
function getDesigns($page = 1, $limit = 12) {
    try {
        $offset = ($page - 1) * $limit;
        $stmt = db()->prepare("
            SELECT d.*, u.full_name, u.profile_image 
            FROM designs d 
            JOIN users u ON d.user_id = u.id 
            WHERE d.is_published = TRUE 
            ORDER BY d.created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get design by ID
 */
function getDesignById($id) {
    try {
        $stmt = db()->prepare("
            SELECT d.*, u.full_name, u.profile_image, u.id as user_id 
            FROM designs d 
            JOIN users u ON d.user_id = u.id 
            WHERE d.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Get user's books
 */
function getUserBooks($userId) {
    try {
        $stmt = db()->prepare("
            SELECT * FROM books 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get user's designs
 */
function getUserDesigns($userId) {
    try {
        $stmt = db()->prepare("
            SELECT * FROM designs 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get total books count
 */
function getTotalBooks() {
    try {
        $stmt = db()->query("SELECT COUNT(*) as count FROM books WHERE is_published = TRUE");
        $result = $stmt->fetch();
        return $result['count'];
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Get total designs count
 */
function getTotalDesigns() {
    try {
        $stmt = db()->query("SELECT COUNT(*) as count FROM designs WHERE is_published = TRUE");
        $result = $stmt->fetch();
        return $result['count'];
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Get total users count
 */
function getTotalUsers() {
    try {
        $stmt = db()->query("SELECT COUNT(*) as count FROM users WHERE is_active = TRUE");
        $result = $stmt->fetch();
        return $result['count'];
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Increment view count
 */
function incrementViewCount($type, $id) {
    try {
        $table = $type === 'book' ? 'books' : 'designs';
        $stmt = db()->prepare("UPDATE $table SET views = views + 1 WHERE id = ?");
        $stmt->execute([$id]);
    } catch (Exception $e) {
        // Silently fail
    }
}

/**
 * Format date to Arabic
 */
function formatDateArabic($date) {
    $months = [
        'January' => 'يناير',
        'February' => 'فبراير',
        'March' => 'مارس',
        'April' => 'أبريل',
        'May' => 'مايو',
        'June' => 'يونيو',
        'July' => 'يوليو',
        'August' => 'أغسطس',
        'September' => 'سبتمبر',
        'October' => 'أكتوبر',
        'November' => 'نوفمبر',
        'December' => 'ديسمبر'
    ];
    
    $formatted = date('d F Y', strtotime($date));
    foreach ($months as $en => $ar) {
        $formatted = str_replace($en, $ar, $formatted);
    }
    return $formatted;
}

/**
 * Get top creators
 */
function getTopCreators($limit = 4) {
    try {
        $stmt = db()->prepare("
            SELECT u.id, u.full_name, u.profile_image, u.user_type, 
                   COUNT(b.id) as books_count, COUNT(d.id) as designs_count
            FROM users u
            LEFT JOIN books b ON u.id = b.user_id AND b.is_published = TRUE
            LEFT JOIN designs d ON u.id = d.user_id AND d.is_published = TRUE
            WHERE u.is_active = TRUE
            GROUP BY u.id
            ORDER BY (COUNT(b.id) + COUNT(d.id)) DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}
?>
