<?php
// Sanitize input data
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Format date
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

// Format time
function formatTime($time, $format = 'g:i A') {
    return date($format, strtotime($time));
}

// Get available time slots
function getAvailableTimeSlots($date) {
    global $pdo;
    
    // Default working hours
    $startTime = strtotime('09:00');
    $endTime = strtotime('17:00');
    $interval = 30 * 60; // 30 minutes in seconds
    
    // Get booked appointments for the date
    $stmt = $pdo->prepare("SELECT appointment_time FROM appointments 
                          WHERE appointment_date = ? AND status = 'confirmed'");
    $stmt->execute([$date]);
    $bookedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Convert booked times to timestamps
    $bookedTimestamps = array_map('strtotime', $bookedTimes);
    
    // Generate all possible time slots
    $slots = [];
    for ($time = $startTime; $time <= $endTime; $time += $interval) {
        if (!in_array($time, $bookedTimestamps)) {
            $slots[] = date('H:i:s', $time);
        }
    }
    
    return $slots;
}

// Send email using PHPMailer
function sendEmail($to, $subject, $body) {
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;
        
        // Recipients
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

// Get all products by category
function getProductsByCategory($category_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
    $stmt->execute([$category_id]);
    return $stmt->fetchAll();
}

// Get all product categories
function getProductCategories() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM product_categories");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get product by ID
function getProductById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get cart items
function getCartItems() {
    return $_SESSION['cart'] ?? [];
}

// Add to cart
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Remove from cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update cart quantity
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Calculate cart total
function calculateCartTotal() {
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product = getProductById($product_id);
            if ($product) {
                $total += $product['price'] * $quantity;
            }
        }
    }
    return $total;
}

// Get all services
function getAllServices() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM services");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get service by ID
function getServiceById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get user appointments
function getUserAppointments($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, s.price as service_price 
                          FROM appointments a 
                          JOIN services s ON a.service_id = s.id 
                          WHERE a.user_id = ? 
                          ORDER BY a.appointment_date DESC, a.appointment_time DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Get all appointments (admin)
function getAllAppointments($status = null) {
    global $pdo;
    
    $sql = "SELECT a.*, s.name as service_name, s.price as service_price, 
                   u.first_name, u.last_name, u.email, u.phone 
            FROM appointments a 
            JOIN services s ON a.service_id = s.id 
            JOIN users u ON a.user_id = u.id
            ORDER BY appointment_date DESC, appointment_time DESC";
            
    if ($status) {
        $sql .= " WHERE a.status = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$status]);
    } else {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    
    return $stmt->fetchAll();
}

// Get appointment by ID
function getAppointmentById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, s.price as service_price, 
                                  u.first_name, u.last_name, u.email, u.phone 
                           FROM appointments a 
                           JOIN services s ON a.service_id = s.id 
                           JOIN users u ON a.user_id = u.id 
                           ORDER BY appointment_date DESC, appointment_time DESC
                           WHERE a.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get appointment counts for dashboard
function getAppointmentCounts() {
    global $pdo;
    
    $counts = [
        'today' => 0,
        'week' => 0,
        'month' => 0,
        'pending' => 0,
        'confirmed' => 0,
        'completed' => 0,
        'declined' => 0
    ];
    
    // Today's appointments
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments 
                          WHERE appointment_date = CURDATE()");
    $stmt->execute();
    $counts['today'] = $stmt->fetchColumn();
    
    // This week's appointments
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments 
                          WHERE YEARWEEK(appointment_date, 1) = YEARWEEK(CURDATE(), 1)");
    $stmt->execute();
    $counts['week'] = $stmt->fetchColumn();
    
    // This month's appointments
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments 
                          WHERE YEAR(appointment_date) = YEAR(CURDATE()) 
                          AND MONTH(appointment_date) = MONTH(CURDATE())");
    $stmt->execute();
    $counts['month'] = $stmt->fetchColumn();
    
    // Status counts
    $stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM appointments GROUP BY status");
    $stmt->execute();
    $statusCounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    $counts = array_merge($counts, $statusCounts);
    
    return $counts;
}

// Upload product image
function uploadProductImage($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    if (!in_array($file['type'], $allowed_types)) {
        return false;
    }
    
    if ($file['size'] > $max_size) {
        return false;
    }
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $destination = __DIR__ . '/../assets/uploads/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    }
    
    return false;
}

// Get all products
function getAllProducts() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get product category by ID
function getProductCategoryById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM product_categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get product category name
function getProductCategoryName($id) {
    $category = getProductCategoryById($id);
    return $category ? $category['name'] : 'Uncategorized';
}

?>