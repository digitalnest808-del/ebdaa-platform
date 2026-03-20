<?php
/**
 * Sample Data Insertion Script
 * Run this script to populate the database with sample data
 */

require_once 'database.php';

try {
    // Hash passwords
    $password_hash = password_hash('password123', PASSWORD_BCRYPT);
    
    // Insert sample users
    $users_data = [
        ['د. أحمد العلي', 'ahmed@example.com', $password_hash, 'كاتب'],
        ['ليلى المصممة', 'laila@example.com', $password_hash, 'مصمم'],
        ['محمد الفيصل', 'mohamed@example.com', $password_hash, 'كاتب'],
        ['فاطمة الأحمد', 'fatima@example.com', $password_hash, 'مصمم'],
        ['علي الخالد', 'ali@example.com', $password_hash, 'قارئ'],
    ];
    
    $stmt = db()->prepare("INSERT INTO users (full_name, email, password, user_type, is_active) VALUES (?, ?, ?, ?, 1)");
    
    foreach ($users_data as $user) {
        $stmt->execute($user);
    }
    
    echo "✓ تم إدراج المستخدمين بنجاح<br>";
    
    // Insert sample books
    $books_data = [
        [1, 'أسرار العقل البشري', 'كتاب رائع عن العقل البشري وكيفية تطويره', 'تطوير ذات', 1],
        [3, 'فن الكتابة الإبداعية', 'دليل شامل لتعلم الكتابة الإبداعية', 'أدب', 1],
        [1, 'الذكاء العاطفي', 'كتاب عن أهمية الذكاء العاطفي في الحياة', 'تطوير ذات', 1],
        [3, 'قصص قصيرة', 'مجموعة من القصص القصيرة الممتعة', 'أدب', 1],
    ];
    
    $stmt = db()->prepare("INSERT INTO books (user_id, title, description, category, is_published) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($books_data as $book) {
        $stmt->execute($book);
    }
    
    echo "✓ تم إدراج الكتب بنجاح<br>";
    
    // Insert sample designs
    $designs_data = [
        [2, 'هوية بصرية - متجر ورد', 'تصميم هوية بصرية كاملة لمتجر ورد', 'هوية بصرية', 1],
        [2, 'شعار - شركة تقنية', 'شعار عصري وحديث لشركة تقنية', 'شعارات', 1],
        [4, 'تصميم موقع - متجر إلكتروني', 'تصميم موقع متجر إلكتروني احترافي', 'تصميم ويب', 1],
        [4, 'بطاقات عمل - شركة استشارات', 'تصميم بطاقات عمل احترافية', 'طباعة', 1],
    ];
    
    $stmt = db()->prepare("INSERT INTO designs (user_id, title, description, category, is_published) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($designs_data as $design) {
        $stmt->execute($design);
    }
    
    echo "✓ تم إدراج التصاميم بنجاح<br>";
    
    echo "<br><strong>✓ تم إدراج جميع البيانات التجريبية بنجاح!</strong><br>";
    echo "بيانات الدخول:<br>";
    echo "البريد الإلكتروني: ahmed@example.com<br>";
    echo "كلمة المرور: password123<br>";
    
} catch (Exception $e) {
    echo "✗ خطأ: " . $e->getMessage();
}
?>
